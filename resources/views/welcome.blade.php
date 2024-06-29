
@extends('layouts.app')

@push('style')
    <style>
        form{
             margin: 50px;
        }
        td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }
       tr:nth-child(even) {
            background-color: #dddddd;
            }
    </style>
@endpush

@section('content')
    <div class="card container mt-5 m-4 g-3">
        <div class="container">
            <form id="productForm">
                @csrf
                <div class="row mb-3">
                    <label for="productName" class="col-sm-2 col-form-label">Product name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="productName">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="quantityInStock" class="col-sm-2 col-form-label">Quantity in stock</label>
                    <div class="col-sm-10">
                        <input type="number" name="quantity" class="form-control" id="quantityInStock">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="price" class="col-sm-2 col-form-label">Price per item</label>
                    <div class="col-sm-10">
                        <input type="number" name="price" class="form-control" id="price">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill">Submit</button>
            </form>
    
            <div id="successMessage" class="alert alert-success mt-3" style="display:none;">
                Product saved successfully!
            </div>
        </div>
    </div>

    <div class=" mt-5 m-4 ">
<table id="products-table" style="width: 100%" >
        <thead>
            <tr>
                <th>Product name</th>
                <th>Quantity in stock</th>
                <th>Price per item</th>
                <th>Datetime submitted</th>
                <th>Total value number</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
    
    

@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#productForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('products.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#successMessage').show();
                    $('#productForm')[0].reset();
                },
                error: function(response) {
                    console.log("Error:", response);
                }
            });
        });
    });

    $(document).ready(function() {
        // Function to fetch and update data
        function fetchProducts() {
            $.ajax({
                url: '{{ route("json.data") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear existing table rows
                    $('#products-table tbody').empty();
                    
                    // Append new rows based on fetched data
                    $.each(data, function(index, product) {
                        $('#products-table tbody').append(`
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${product.price}</td>
                                <td>${product.created_at}</td>
                                <td>${product.quantity * product.price}</td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', error);
                }
            });
        }

        // Initial fetch when page loads
        fetchProducts();

        // Refresh data every second
        setInterval(fetchProducts, 1000);
    });
</script>
@endpush