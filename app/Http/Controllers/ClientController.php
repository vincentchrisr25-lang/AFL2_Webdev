<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Contracts\View\View;

class ClientController extends Controller
{
    public function galleryClients(): View
    {
        return $this->listClients('gallery', 'gallery', 'gallery.detail', 'No client stories yet.');
    }

    public function photoboothClients(): View
    {
        return $this->listClients(
            'photobooth',
            'photobooth',
            'photobooth.detail',
            'Belum ada klien photobooth.'
        );
    }

    public function lumenaraClients(): View
    {
        return $this->listClients(
            'lumenara',
            'lumenara',
            'lumenara.detail',
            'Belum ada klien lumenara.'
        );
    }

    public function livestreamingClients(): View
    {
        return $this->listClients(
            'livestream',
            'livestreaming',
            'livestreaming.detail',
            'Belum ada klien livestream.'
        );
    }

    public function galleryDetail(Client $client): View
    {
        return $this->showClient('gallery', $client, 'gallerydetail');
    }

    public function photoboothDetail(Client $client): View
    {
        return $this->showClient('photobooth', $client, 'photoboothdetail');
    }

    public function lumenaraDetail(Client $client): View
    {
        return $this->showClient('lumenara', $client, 'lumenaradetail');
    }

    public function livestreamDetail(Client $client): View
    {
        return $this->showClient('livestream', $client, 'livestreamdetail');
    }

    private function listClients(string $type, string $view, string $detailRoute, string $emptyMessage): View
    {
        $clients = Client::query()
            ->where('client_type', $type)
            ->latest()
            ->get();

        return view($view, [
            'clients' => $clients,
            'detailRoute' => $detailRoute,
            'emptyMessage' => $emptyMessage,
        ]);
    }

    private function showClient(string $type, Client $client, string $view): View
    {
        abort_unless($client->client_type === $type, 404);

        $client->load(['videos', 'photos']);

        return view($view, [
            'client' => $client,
        ]);
    }
}
