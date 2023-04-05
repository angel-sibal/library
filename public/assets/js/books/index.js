$(document).ready(function () {
    $("#dropdownActionButton").hide();

    $('input:checkbox[name=ids], #select_all_ids').change(function(e) {
        if ($('input:checkbox[name=ids]:checked').length !== 0) {
            $("#dropdownActionButton").show();
        } else {
            $("#dropdownActionButton").hide();
        }
    });

    $("#select_all_ids").click(function(){
        $('.checkbox_ids').prop('checked', $(this).prop('checked'));
    });

    var all_ids = [];

    $(".delete-button").click(function(e){
        e.preventDefault();
        var book_id = $(this).attr('data-book-id');
        all_ids.push(book_id);

        deleteBook(all_ids);
    });

    $(".delete-all-button").click(function(e){
        e.preventDefault();
        $('input:checkbox[name=ids]:checked').each(function (){
            all_ids.push($(this).val());
        });

        deleteBook(all_ids);
    });

    function deleteBook(book_ids) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: delete_url,
            type: "DELETE",
            data: {
                ids: book_ids,
            },
            success: function(response) {
                location.reload();
            }
        });
    }
});
  