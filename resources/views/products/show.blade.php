<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Produto</title>
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
    <h1 class="mt-5">Detalhes do Produto</h1>

    <!-- Informações do produto -->
    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" id="nome" value="{{ $product->name }}" readonly>
    </div>

    <div class="form-group">
        <label for="descricao">Descrição</label>
        <textarea class="form-control" id="descricao" readonly>{{ $product->description }}</textarea>
    </div>

    <div class="form-group">
        <label for="preco">Preço Unitário</label>
        <input type="text" class="form-control" id="preco" value="${{ number_format($product->unit_price, 2) }}" readonly>
    </div>

    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Editar Produto</a>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Voltar</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
