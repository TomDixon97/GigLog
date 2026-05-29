<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ArtistController {

    public function __construct(private ArtistRepository $artists){}


    public function list(Request $request, Response $response) : Response {
        $artists = $this->artists->findAll();
        $data = array_map(fn(Artist $a) => $a->toArray(), $artists);
        return $this->json($response, $data);
    }

    public function listById(Request $request, Response $response, array $args) : Response {
        $artist = $this->artists->find((int) $args['id']);
        if (!$artist) {
            return $this->json($response,["message" =>"No artist found"], 404);
        }
        return $this->json($response, $artist->toArray());
    }

    public function create(Request $request, Response $response) : Response {
        $data = $request->getParsedBody();
        if (empty($data['name'])){
            return $this->json($response, ["message" => "Name is required"], 400);
        }
        $artist = new Artist(name: $data['name'], genre: $data['genre'] ?? null);
        $this->artists->save($artist);
        return $this->json($response, $artist->toArray(), 201);

    }

    public function update(Request $request, Response $response, array $args) : Response {
        $data = $request->getParsedBody();
        $artist = $this->artists->find((int) $args['id']);
        if (!$artist) {
            return $this->json($response, ["message" => "No artist found"], 404);
        }

        if (isset($data['name'])) {
            $artist->setName($data['name']);
        }

        if (isset($data['genre'])){
            $artist->setGenre($data['genre']);
        }

        $this->artists->save($artist);
        return $this->json($response, $artist->toArray(), 201);

    }


    public function delete(Request $request, Response $response, array $args): Response
    {
        $artist = $this->artists->find((int) $args['id']);
        if (!$artist) {
            return $this->json($response, ["message" => "No artist found"], 404);
        }
        $this->artists->remove($artist);
        return $response->withStatus(204);
    }
    private function json (Response $response, mixed $data, int $status = 200): Response {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }


}