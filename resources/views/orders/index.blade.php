<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>
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
        <h1 class="mt-5">Lista de Pedidos</h1>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createOrderModal">
            Criar Pedido
        </button>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->client->nome }}</td> <!-- Assuming 'nome' is the attribute for client's name -->
                    <td>{{ $order->status }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">Visualizar</a>
                        <!-- Botão de Excluir com Modal de Confirmação -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal{{ $order->id }}">
                            Excluir
                        </button>
                    </td>
                </tr>
                <!-- Modal de confirmação de exclusão para o pedido atual -->
                <div class="modal fade" id="confirmDeleteModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $order->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmDeleteModalLabel{{ $order->id }}">Confirmar Exclusão</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Tem certeza que deseja excluir o pedido ID: {{ $order->id }}?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <!-- Formulário de Exclusão -->
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal de criação de pedido -->
    <div class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="createOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="create-order-form-modal" action="{{ route('orders.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="createOrderModalLabel">Criar Pedido</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="client_id">Cliente</label>
                            <select class="form-control" id="client_id" name="client_id">
                                <option value="">Selecione um cliente</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status_create_modal">Status</label>
                            <select class="form-control" id="status_create_modal" name="status">
                                <option value="Em Aberto">Em Aberto</option>
                                <option value="Pago">Pago</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description_create_modal">Descrição</label>
                            <textarea class="form-control" id="description_create_modal" name="description"></textarea>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#productSearchModal">
                                    <i class="fa fa-search"></i> Buscar Produtos
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Produtos Adicionados</label>
                            <ul id="added-products-list-create-modal">
                                <!-- Aqui serão listados os produtos adicionados -->
                            </ul>
                        </div>

                        <div id="added-products-inputs-create-modal">
                            <!-- Inputs ocultos para produtos adicionados -->
                        </div>

                        <hr>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Criar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Busca de Produtos -->
    <div class="modal fade" id="productSearchModal" tabindex="-1" role="dialog" aria-labelledby="productSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productSearchModalLabel">Buscar Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Campo de busca -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="searchProductInput" placeholder="Buscar por nome do produto">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchProductsByName($('#searchProductInput').val())"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    
                    <!-- Lista de produtos encontrados -->
                    <ul id="searchProductList" class="list-group">
                        <!-- Os produtos serão adicionados dinamicamente aqui via JavaScript -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="addSearchedProduct()">Adicionar Produto Selecionado</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
   // Variável global para armazenar os produtos buscados
   var searchedProducts = [];
   var products = {!! json_encode($products) !!};

   // Função para redefinir a seleção de produtos ao abrir o modal
   $('#productSearchModal').on('show.bs.modal', function (e) {
       // Limpar a lista de produtos buscados
       $('#searchProductList').empty();

       // Redefinir a seleção dos produtos para false
       searchedProducts.forEach(function(product) {
           product.selected = false;
       });

       // Iniciar busca com campo vazio ao carregar o modal
       searchProductsByName('');
   });

   // Função para buscar produtos por nome
   function searchProductsByName(searchTerm) {
       // Limpar lista de produtos antes de adicionar novos resultados
       $('#searchProductList').empty();

       // Filtrar produtos que correspondem ao termo de busca
       searchedProducts = products.filter(function(product) {
           return product.name.toLowerCase().includes(searchTerm.toLowerCase());
       });

       // Adicionar produtos filtrados à lista
       searchedProducts.forEach(function(product) {
           var buttonLabel = product.selected ? 'Remover' : 'Selecionar';
           var buttonClass = product.selected ? 'btn-danger' : 'btn-primary';

           $('#searchProductList').append('<li class="list-group-item">' + product.name + ' - ' + product.description + ' <button type="button" class="btn btn-sm float-right ' + buttonClass + '" onclick="toggleProductSelection(' + product.id + ')">' + buttonLabel + '</button></li>');
       });
   }

   // Função para alternar a seleção de um produto na lista de busca
   function toggleProductSelection(productId) {
       var index = searchedProducts.findIndex(function(product) {
           return product.id === productId;
       });

       if (index !== -1) {
           searchedProducts[index].selected = !searchedProducts[index].selected;
           updateSelectedProductList();
       }
   }

   // Função para atualizar a lista de produtos selecionados
   function updateSelectedProductList() {
       $('#searchProductList').empty();

       searchedProducts.forEach(function(product) {
           var buttonLabel = product.selected ? 'Remover' : 'Selecionar';
           var buttonClass = product.selected ? 'btn-danger' : 'btn-primary';

           $('#searchProductList').append('<li class="list-group-item">' + product.name + ' - ' + product.description + ' <button type="button" class="btn btn-sm float-right ' + buttonClass + '" onclick="toggleProductSelection(' + product.id + ')">' + buttonLabel + '</button></li>');
       });
   }

   // Função para adicionar produto selecionado à lista de produtos a serem adicionados ao pedido
   function addSearchedProduct() {
       searchedProducts.forEach(function(product) {
           if (product.selected) {
               var existingProduct = $('#added-product-' + product.id);

               if (existingProduct.length > 0) {
                   // Produto já existe na lista, aumentar a quantidade
                   var currentQuantity = parseInt($('#added-product-quantity-' + product.id).text());
                   var newQuantity = currentQuantity + 1;
                   $('#added-product-quantity-' + product.id).text(newQuantity);
                   $('input[name="products[' + product.id + '][quantity]"]').val(newQuantity);
               } else {
                   // Produto não existe na lista, adicionar novo
                   $('#added-products-list-create-modal').append(
                       '<li id="added-product-' + product.id + '">' + 
                       product.name + 
                       ' - Quantidade: <span id="added-product-quantity-' + product.id + '">1</span> ' + 
                       '<a href="#" class="text-danger" onclick="removeAddedProduct(event, ' + product.id + ')">Excluir</a>' + 
                       '<input type="number" class="ml-2 form-control" style="width: 60px;" id="edit-quantity-' + product.id + '" value="1" min="1" onchange="updateQuantity(' + product.id + ')">' +
                       '</li>'
                   );

                   $('#added-products-inputs-create-modal').append(
                       '<input type="hidden" name="products[' + product.id + '][product_id]" value="' + product.id + '">' +
                       '<input type="hidden" name="products[' + product.id + '][quantity]" value="1">'
                   );
               }
           }
       });

       $('#productSearchModal').modal('hide');
       saveProductsToLocalStorage();
       updateTotalProductsCount();
   }

   // Função para salvar a lista de produtos no localStorage
   function saveProductsToLocalStorage() {
       var productsToSave = [];
       $('#added-products-list-create-modal li').each(function() {
           var productId = $(this).attr('id').replace('added-product-', '');
           var quantity = $('#added-product-quantity-' + productId).text();
           productsToSave.push({ id: productId, quantity: quantity });
       });
       localStorage.setItem('addedProducts', JSON.stringify(productsToSave));
   }

    // Função para carregar a lista de produtos do localStorage
    function loadProductsFromLocalStorage() {
        // Verificar se está no modal de criação de pedido
        if ($('#createOrderModal').hasClass('show')) {
            return; // Não fazer nada se estiver no modal de criação de pedido
        }

        var savedProducts = JSON.parse(localStorage.getItem('addedProducts')) || [];
        var productsToAdd = [];

        savedProducts.forEach(function(product) {
            // Verificar se o produto ainda existe na lista de produtos
            var existingProduct = products.find(p => p.id == product.id);
            if (existingProduct) {
                productsToAdd.push({
                    id: product.id,
                    name: existingProduct.name,
                    description: existingProduct.description,
                    quantity: product.quantity
                });
            }
        });

        // Limpar lista atual
        $('#added-products-list-create-modal').empty();
        $('#added-products-inputs-create-modal').empty();

        // Adicionar produtos válidos à lista visual
        productsToAdd.forEach(function(product) {
            $('#added-products-list-create-modal').append(
                '<li id="added-product-create-modal-' + product.id + '">' + 
                product.name + 
                ' - Quantidade: <span id="added-product-create-modal-quantity-' + product.id + '">' + product.quantity + '</span> ' + 
                '<a href="#" class="text-danger" onclick="removeAddedProductCreateModal(event, ' + product.id + ')">Excluir</a>' + 
                '</li>'
            );

            $('#added-products-inputs-create-modal').append(
                '<input type="hidden" name="products[' + product.id + '][product_id]" value="' + product.id + '">' +
                '<input type="hidden" name="products[' + product.id + '][quantity]" value="' + product.quantity + '">'
            );
        });
    }

    // Inicialização da lista de produtos
    $(document).ready(function() {
        // Atualizar contagem total inicialmente
        updateTotalProductsCount();

        // Iniciar busca com campo vazio ao carregar o modal
        searchProductsByName('');

        // Atualizar lista de produtos conforme o usuário digita
        $('#searchProductInput').on('input', function() {
            searchProductsByName($(this).val());
        });

        // Carregar produtos salvos do localStorage após a página ser completamente carregada
        loadProductsFromLocalStorage();
    });

    // Função para abrir o modal de criação de pedido
    $('#createOrderModal').on('show.bs.modal', function (e) {
        // Limpar a lista de produtos adicionados no modal de criação de pedido
        $('#added-products-list-create-modal').empty();
        $('#added-products-inputs-create-modal').empty();
    });


    // Função para remover produto adicionado
    function removeAddedProduct(event, productId) {
        event.preventDefault();

        // Animação para ocultar o produto antes de remover
        $('#added-product-' + productId).fadeOut('fast', function() {
            $(this).remove();
            updateTotalProductsCount();
        });

        // Remover o produto dos inputs escondidos
        $('#added-products-inputs-create-modal').find('input[name="products[' + productId + '][product_id]"]').remove();
        $('#added-products-inputs-create-modal').find('input[name="products[' + productId + '][quantity]"]').remove();

        // Remover o produto do localStorage, se aplicável
        var savedProducts = JSON.parse(localStorage.getItem('addedProducts')) || [];
        var updatedProducts = savedProducts.filter(function(product) {
            return product.id !== productId;
        });
        localStorage.setItem('addedProducts', JSON.stringify(updatedProducts));
    }

    // Função para atualizar o total de produtos
    function updateTotalProductsCount() {
        var totalProducts = $('#added-products-list-create-modal li').length;
        $('#totalProductsCount').text(totalProducts);
    }


   // Função para atualizar a quantidade de um produto
   function updateQuantity(productId) {
       var newQuantity = $('#edit-quantity-' + productId).val();
       $('#added-product-quantity-' + productId).text(newQuantity);
       $('input[name="products[' + productId + '][quantity]"]').val(newQuantity);
       saveProductsToLocalStorage();
   }

   // Função para limpar lista de produtos adicionados
   function clearAddedProductsList() {
       $('#added-products-list-create-modal').empty();
       $('#added-products-inputs-create-modal').empty();
       localStorage.removeItem('addedProducts');
       updateTotalProductsCount();
   }

   // Função para criar o pedido
   function createOrder() {
       var formData = $('#createOrderForm').serialize();

       $.ajax({
           url: '{{ route('orders.store') }}',
           type: 'POST',
           data: formData,
           success: function(response) {
               alert('Pedido criado com sucesso.');
               // Limpar formulário ou realizar ação após a criação bem sucedida
               clearAddedProductsList();
           },
           error: function(error) {
               alert('Erro ao criar pedido.');
           }
       });
   }

    // Script para manipular o modal de confirmação de exclusão
    $(document).ready(function() {
            // Escutar o clique no botão de exclusão
            $('.btn-confirm-delete').on('click', function(e) {
                e.preventDefault(); // Evitar o comportamento padrão do botão

                var modalId = $(this).data('target'); // Pegar o ID do modal alvo
                var form = $(modalId).find('form'); // Encontrar o formulário dentro do modal

                // Mostrar o modal de confirmação
                $(modalId).modal('show');

                // Escutar o clique no botão de confirmar no modal de confirmação
                $(modalId).find('.btn-confirm-exclude').on('click', function() {
                    form.submit(); // Enviar o formulário de exclusão
                });
            });
        });
   
</script>


</body>
</html>
