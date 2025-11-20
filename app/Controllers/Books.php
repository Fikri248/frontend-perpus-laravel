<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class Books extends Controller
{
    protected $apiBase;
    protected $client;

    public function __construct()
    {
        $this->apiBase = env('API_BASE_URL') ?: 'http://127.0.0.1:8000/api';
        $this->apiBase = rtrim($this->apiBase, '/');

        $this->client = Services::curlrequest([
            'base_uri' => $this->apiBase . '/',
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 5,
        ]);
    }

    protected function buildUrl(string $path = ''): string
    {
        $path = ltrim($path, '/');
        return $this->apiBase . '/' . $path;
    }

    public function index()
    {
        $q = $this->request->getGet('q');
        $kategori = $this->request->getGet('kategori');
        $status = $this->request->getGet('status');
        $page = $this->request->getGet('page') ?? 1;
        $per_page = $this->request->getGet('per_page') ?? 10;

        $params = [
            'query' => array_filter([
                'q' => $q,
                'kategori' => $kategori,
                'status' => $status,
                'page' => $page,
                'per_page' => $per_page,
            ], function ($v) { return $v !== null && $v !== ''; })
        ];

        try {
            $url = $this->buildUrl('books');
            $res = $this->client->get($url, $params);
            $body = json_decode($res->getBody(), true);

            if (!is_array($body)) {
                $body = ['data' => [], 'meta' => ['total' => 0, 'per_page' => (int)$per_page, 'current_page' => (int)$page, 'last_page' => 1]];
            }

            return view('books/index', [
                'books' => $body,
                'q' => $q,
                'kategori' => $kategori,
                'status' => $status,
            ]);
        } catch (\Throwable $e) {
            return view('books/index', [
                'books' => ['data' => [], 'meta' => ['total' => 0, 'per_page' => (int)$per_page, 'current_page' => (int)$page, 'last_page' => 1]],
                'q' => $q,
                'kategori' => $kategori,
                'status' => $status,
            ]);
        }
    }

    public function listingAjax()
    {
        $q = $this->request->getGet('q');
        $kategori = $this->request->getGet('kategori');
        $status = $this->request->getGet('status');
        $sort_by = $this->request->getGet('sort_by') ?? 'id';
        $sort_order = $this->request->getGet('sort_order') ?? 'asc';
        $per_page = $this->request->getGet('per_page') ?? 10;
        $page = $this->request->getGet('page') ?? 1;

        $params = [
            'query' => array_filter([
                'q' => $q,
                'kategori' => $kategori,
                'status' => $status,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order,
                'per_page' => $per_page,
                'page' => $page,
            ], function ($v) { return $v !== null && $v !== ''; })
        ];

        try {
            $url = $this->buildUrl('books');
            $res = $this->client->get($url, $params);
            $body = json_decode($res->getBody(), true);
            return $this->response->setJSON($body);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['error' => true])->setStatusCode(500);
        }
    }

    public function create()
    {
        return view('books/form', ['mode' => 'create', 'book' => null]);
    }

    public function store()
    {
        $data = [
            'judul' => $this->request->getPost('judul'),
            'pengarang' => $this->request->getPost('pengarang'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'jumlah_halaman' => $this->request->getPost('jumlah_halaman'),
            'kategori' => $this->request->getPost('kategori'),
            'isbn' => $this->request->getPost('isbn'),
        ];

        $url = $this->buildUrl('books');
        $this->client->post($url, ['json' => $data]);
        return redirect()->to('/books');
    }

    public function edit($id)
    {
        $url = $this->buildUrl("books/{$id}");
        $res = $this->client->get($url);

        if ($res->getStatusCode() !== 200) {
            return redirect()->to('/books');
        }
        $book = json_decode($res->getBody(), true);
        return view('books/form', ['mode' => 'edit', 'book' => $book]);
    }

    public function update($id)
    {
        $data = [
            'judul' => $this->request->getPost('judul'),
            'pengarang' => $this->request->getPost('pengarang'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'jumlah_halaman' => $this->request->getPost('jumlah_halaman'),
            'kategori' => $this->request->getPost('kategori'),
            'isbn' => $this->request->getPost('isbn'),
            'status' => $this->request->getPost('status'),
            'borrowed_by' => $this->request->getPost('borrowed_by'),
        ];

        $url = $this->buildUrl("books/{$id}");
        $this->client->request('PUT', $url, ['json' => array_filter($data, function($v){return $v !== null && $v !== '';})]);
        return redirect()->to('/books');
    }

    public function show($id)
    {
        $url = $this->buildUrl("books/{$id}");
        $res = $this->client->get($url);
        
        if ($res->getStatusCode() !== 200) {
            return redirect()->to('/books');
        }
        $book = json_decode($res->getBody(), true);
        return view('books/show', ['book' => $book]);
    }

    public function delete($id)
    {
        $url = $this->buildUrl("books/{$id}");
        $this->client->delete($url);
        return redirect()->to('/books');
    }

    public function borrow($id)
    {
        $borrowed_by = $this->request->getPost('borrowed_by');
        $url = $this->buildUrl("books/{$id}/borrow");
        $this->client->post($url, ['json' => ['borrowed_by' => $borrowed_by]]);
        return redirect()->to('/books');
    }

    public function return($id)
    {
        $url = $this->buildUrl("books/{$id}/return");
        $this->client->post($url);
        return redirect()->to('/books');
    }
}
