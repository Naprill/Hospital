$(function() {
    document.querySelector('input[list]').addEventListener('input', function(e) {

        var input = e.target,
            list = input.getAttribute('list'),
            options = document.querySelectorAll('#' + list + ' option'),
            hiddenInput = document.getElementById(input.id + '-hidden'),
            inputValue = input.value;

        console.log(input.id);
        console.log(hiddenInput);
        hiddenInput.value = inputValue;

        for(var i = 0; i < options.length; i++) {
            var option = options[i];

            if(option.innerText === inputValue) {
                hiddenInput.value = option.getAttribute('data-value');
                break;
            }
        }
    });
});
