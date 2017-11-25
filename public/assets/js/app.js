$(document).on('change', '.category-selector', function () {
    var categoryId = $(this).val();
    var merchantId = $(this).attr('data-merchant-id');

    // Change the others on the page.
    $('.category-selector').filter('[data-merchant-id=' + merchantId + ']').val(categoryId);

    $.post(
        '/api/merchants/' + merchantId,
        {
            '_method': 'PATCH',
            categoryId: categoryId
        }
    );
});

$(document).on('click', '.edit-merchant-name', function () {
    var $cell = $(this).closest('td');
    var merchantId = $cell.attr('data-merchant-id');
    var currentName = $cell.find('.merchant-name').text();

    var newName = window.prompt(
        'Enter a new name for the merchant',
        currentName
    );

    if (!newName) {
        return false;
    }

    // Change the names on the page.
    $('.merchant-cell').filter('[data-merchant-id=' + merchantId + ']').find('.merchant-name').text(newName);

    $.post(
        '/api/merchants/' + merchantId,
        {
            '_method': 'PATCH',
            name: newName
        }
    );
});
