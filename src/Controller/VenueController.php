<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Venue;
use App\Repository\VenueRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class VenueController
{

    public function __construct(private VenueRepository $venues){}


    public function list(Request $request, Response $response) : Response{
        $venues = $this->venues->findAll();

        $data = array_map(fn(Venue $v ) => $v->toArray(), $venues);

        return $this->json($response, $data);
    }


    public function listById(Request $request, Response $response, array $args) : Response{
        $venue = $this->venues->find((int) $args['id']);

        if ($venue === null) {
            return $this->json($response, ["message" => "No venue found."], 404);
        }
        return $this->json($response, $venue->toArray());
    }


    public function create(Request $request, Response $response) : Response{
        $data = (array) $request->getParsedBody();

        if (empty($data['name'])) {
            return $this->json($response, ["message" => "Name is required."], 400);
        }

        $venue = new Venue(
            name: $data['name'],
            capacity: (int) ($data['capacity'] ?? 0),
            city: $data['city'] ?? 'Nottingham',
        );

        $this->venues->save($venue);
        return $this->json($response, $venue.toArray(), 201);


    }


    public function update(Request $request, Response $response, array $args) : Response{
        $venue = $this->venues->find((int) $args['id']);

        if ($venue === null) {

            return $this->json($response, ["message" => "No venue found."], 404);
        }
        $data = (array) $request->getParsedBody();

        if (isset($data['name'])) {
            $venue->setName($data['name']);
        }
        if (isset($data['capacity'])) {
            $venue->setCapacity((int) $data['capacity']);
        }
        if (isset($data['city'])) {
            $venue->setCity($data['city']);
        }


        $this->venues->save($venue);
        return $this->json($response, $venue->toArray());

    }

    private function json (Response $response, mixed $data, int $status = 200): Response {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }


}

