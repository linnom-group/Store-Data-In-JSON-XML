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