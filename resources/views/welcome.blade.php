<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Trendyol API</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <a class="navbar-brand" href="/">TrendyolAPI</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                  </li>
                </ul>
                <form action="{{route('homeKeyword')}}" method="GET" class="d-flex" role="search">
                    <select name="keyword_type" class="form-select">
                        <option value="">--Search Type--</option>
                        <option {{@$_GET["keyword_type"] == "title" ? "selected" : ""}} value="title">Title</option>
                        <option {{@$_GET["keyword_type"] == "productMainId" ? "selected" : ""}} value="productMainId">ProductMainId</option>
                        <option {{@$_GET["keyword_type"] == "productCode" ? "selected" : ""}} value="productCode">ProductCode</option>
                    </select>
                    <input class="form-control me-2" type="search" name="keyword" placeholder="Search" value="{{@$_GET["keyword"]}}" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
              </div>
            </div>
          </nav>


          <div class="w-100 p-4">
            @if (session()->has("message"))
                <div class="alert alert-primary">
                    {{session()->get('message')}}
                </div>
            @endif
            
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3>Product Listing</h3>
                <a href="{{route('checkBatchStatus',['batchRequestId'=>session()->get('batchRequestId')])}}" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i> Yenile</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                      <tr class="align-middle text-center">
                        <th scope="col">#</th>
                        <th scope="col">MainID</th>
                        <th scope="col">ProductCode</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">Images</th>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">SalePrice</th>
                        <th scope="col">ListPrice</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Attributes</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $key => $product)
                            <tr class="align-middle text-center h-100">
                                <th scope="row">{{$key+1}}</th>
                                <td>
                                    {{$product->productMainId}}
                                    <br>
                                    @if (checkIfProductHasVariants($product->productMainId))
                                        <span class='badge bg-success'>Variant</span>
                                    @else
                                        <span class='badge bg-danger'>Without Variant</span>
                                    @endif
                                    
                                </td>
                                <td>{{$product->productCode}}</td>
                                <td>{{$product->barcode}}</td>
                                <td class="text-start">
                                    @php
                                        $images = json_decode($product->images,true);
                                    @endphp
                                    @foreach ($images as $image)
                                        <a class="text-decoration-none" target="_blank" href="{{$image["url"]}}">
                                            <img width="20" src="{{$image["url"]}}" alt="">
                                        </a>
                                    @endforeach
                                </td>
                                <td class="text-start">{{$product->title}}</td>
                                <td class="text-nowrap">{{$product->categoryName}}</td>
                                <td>{{$product->salePrice}}₺</td>
                                <td>{{$product->listPrice}}₺</td>
                                <td>{{$product->quantity}}</td>
                                <td>
                                    <a role="button" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#attributes-{{$product->id}}">Görüntüle</a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a role="button" data-bs-toggle="modal" data-bs-target="#updateStockAndPriceModal-{{$product->id}}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                        <a target="_blank" href="{{$product->productUrl}}" class="btn btn-sm btn-success"><i class="bi bi-box-arrow-up-right"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="attributes-{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{$product->title}}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4>Attributes</h4>
                                        @php
                                            $attributes = json_decode($product->attributes,true);
                                        @endphp
                                        <ul>
                                            @foreach ($attributes as $attribute)
                                                <li>
                                                    <b>{{$attribute["attributeName"]}}:</b>
                                                    <span>{{$attribute["attributeValue"]}}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="updateStockAndPriceModal-{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form action="{{route('updatePriceAndInventory')}}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">{{$product->title}}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Barcode</label>
                                                    <input type="text" class="form-control" name="barcode" value="{{$product->barcode}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Quantity</label>
                                                    <input type="number" class="form-control" name="quantity" value="{{$product->quantity}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Sale Price</label>
                                                    <input type="text" class="form-control" name="salePrice" value="{{$product->salePrice}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>List Price</label>
                                                    <input type="text" class="form-control" name="listPrice" value="{{$product->listPrice}}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button class="btn btn-sm btn-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="12">
                                    <div class="text-center">
                                        <a href="{{route('getProducts')}}" class="btn btn-primary"> Ürünleri Çek </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                      
                    </tbody>
                  </table>

                  <nav>
                    {{$products->links()}}
                  </nav>
                  
              </div>
          </div>




        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="//cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>  
          <script>
           let table = new DataTable('#myTable');
          </script>
    </body>
</html>
