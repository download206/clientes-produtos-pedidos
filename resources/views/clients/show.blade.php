<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Cliente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/clients">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/orders">Pedidos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/products">Produtos</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="mt-5">Detalhes do Cliente</h1>

    <!-- Informações do cliente -->
    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" id="nome" value="{{ $client->nome }}" readonly>
    </div>

    <div class="form-group">
        <label for="telefone">Telefone</label>
        <input type="text" class="form-control" id="telefone" value="{{ $client->telefone }}" readonly>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" value="{{ $client->email }}" readonly>
    </div>

    <hr>

    <!-- Lista de pedidos associados ao cliente -->
    <div class="form-group">
        <label>Pedidos</label>
        <ul id="order-list">
            @forelse ($client->orders as $order)
                <li id="order-{{ $order->id }}">
                    Status: {{ $order->status }}
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info ml-2">Visualizar</a>
                </li>
            @empty
                <li>Nenhum pedido associado a este cliente.</li>
            @endforelse
        </ul>
    </div>

    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary">Editar Cliente</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
