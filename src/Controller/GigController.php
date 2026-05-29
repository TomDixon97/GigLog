<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Gig;
use App\Repositories\GigRepository;
use App\Repository\ArtistRepository;
use App\Repository\GigRepository;
use App\Repository\VenueRepository;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GigController {
    public function __construct(
        private GigRepository $gigs,
        private VenueRepository $venues,
        private ArtistRepository $artists,
    ) {}

    public function list(Request $request, Response $response) : Response {
        $gigs = $this->gigs->findAll();
        $data = array_map(fn(Gig $gig) => $gig->toArray(), $gigs);

        return $this->json($response, $data);

    }


    public function show(Request $request, Response $response, array $args): Response
    {
        $gig = $this->gigs->find((int) $args['id']);

        if ($gig === null) {
            return $this->json($response, ['error' => 'Gig not found'], 404);
        }

        return $this->json($response, $gig->toArray());
    }

    public function create(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();

        if (empty($data['venue_id']) || empty($data['date'])) {
            return $this->json($response, ['error' => 'venue_id and date are required'], 422);
        }

        $venue = $this->venues->find((int) $data['venue_id']);
        if ($venue === null) {
            return $this->json($response, ['error' => 'Venue not found'], 422);
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d', $data['date']);
        if ($date === false) {
            return $this->json($response, ['error' => 'Invalid date — use YYYY-MM-DD'], 422);
        }

        $gig = new Gig(venue: $venue, date: $date);

        // Optional artist_ids on create. Accept either a comma-separated string
        // ("1,2,3") or an array if the client sends it that way.
        if (!empty($data['artist_ids'])) {
            $ids = is_array($data['artist_ids'])
                ? $data['artist_ids']
                : explode(',', (string) $data['artist_ids']);

            foreach ($ids as $artistId) {
                $artist = $this->artists->find((int) $artistId);
                if ($artist === null) {
                    return $this->json($response, ['error' => "Artist $artistId not found"], 422);
                }
                $gig->addArtist($artist);
            }
        }

        $this->gigs->save($gig);

        return $this->json($response, $gig->toArray(), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $gig = $this->gigs->find((int) $args['id']);
        if ($gig === null) {
            return $this->json($response, ['error' => 'Gig not found'], 404);
        }

        $data = (array) $request->getParsedBody();

        if (isset($data['rating'])) {
            $gig->setRating((int) $data['rating']);
        }
        if (isset($data['venue_id'])) {
            $venue = $this->venues->find((int) $data['venue_id']);
            if ($venue === null) {
                return $this->json($response, ['error' => 'Venue not found'], 422);
            }
            $gig->setVenue($venue);
        }
        if (isset($data['date'])) {
            $date = DateTimeImmutable::createFromFormat('Y-m-d', $data['date']);
            if ($date === false) {
                return $this->json($response, ['error' => 'Invalid date'], 422);
            }
            $gig->setDate($date);
        }

        $this->gigs->save($gig);

        return $this->json($response, $gig->toArray());
    }

    // POST /gigs/{id}/artists — attach an artist to a gig.
    public function attachArtist(Request $request, Response $response, array $args): Response
    {
        $gig = $this->gigs->find((int) $args['id']);
        if ($gig === null) {
            return $this->json($response, ['error' => 'Gig not found'], 404);
        }

        $data = (array) $request->getParsedBody();
        if (empty($data['artist_id'])) {
            return $this->json($response, ['error' => 'artist_id is required'], 422);
        }

        $artist = $this->artists->find((int) $data['artist_id']);
        if ($artist === null) {
            return $this->json($response, ['error' => 'Artist not found'], 422);
        }

        $gig->addArtist($artist);
        $this->gigs->save($gig);

        return $this->json($response, $gig->toArray());
    }

    // DELETE /gigs/{id}/artists/{artistId} — detach an artist from a gig.
    public function detachArtist(Request $request, Response $response, array $args): Response
    {
        $gig = $this->gigs->find((int) $args['id']);
        if ($gig === null) {
            return $this->json($response, ['error' => 'Gig not found'], 404);
        }

        $artist = $this->artists->find((int) $args['artistId']);
        if ($artist === null) {
            return $this->json($response, ['error' => 'Artist not found'], 404);
        }

        $gig->removeArtist($artist);
        $this->gigs->save($gig);

        return $this->json($response, $gig->toArray());
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $gig = $this->gigs->find((int) $args['id']);
        if ($gig === null) {
            return $this->json($response, ['error' => 'Gig not found'], 404);
        }

        $this->gigs->remove($gig);

        return $response->withStatus(204);
    }

    private function json(Response $response, mixed $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}

}