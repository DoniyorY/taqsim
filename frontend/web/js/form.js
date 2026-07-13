$('#form-c').submit(function () {
    try {
        $(this).find('button[type=submit]').prop('disabled', true);
    } catch (error) {
        console.log(error)
    }
});

