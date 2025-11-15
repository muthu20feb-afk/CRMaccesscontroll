$(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

    $('#email').on('blur', function() {
        let email = $(this).val();
        if (email !== '') {
            $.ajax({
                type: 'POST',
                url: Login,
                data: { email: email },
                success: function(data) {
                    let msg = $('#email-status');
                    if (data.status) {
                        msg.text('Email exists').css('color', 'green').fadeIn();
                    } else {
                        msg.text('Email not found').css('color', 'red').fadeIn();
                    }

                    setTimeout(() => {
                        msg.fadeOut();
                    }, 2000);
                }
            });
        }
    });

    // Password check
    $('#password').on('blur', function() {
        let email = $('#email').val();
        let password = $(this).val();
        if (email !== '' && password !== '') {
            $.ajax({
                type: 'POST',
                url: Password,
                data: { email: email, password: password },
                success: function(data) {
                    let msg = $('#password-status');
                    if (data.status) {
                        msg.text(' Password correct').css('color', 'green').fadeIn();
                    } else {
                        msg.text(' Wrong password').css('color', 'red').fadeIn();
                    }

                    setTimeout(() => {
                        msg.fadeOut();
                    }, 2000);
                }
            });
        }
    });


    // Toggle Password Eye
    $('#togglePassword').on('click', function(e) {
        e.preventDefault();
        const passwordInput = $('#password');
        const eyeOpen = $('#eyeOpen');
        const eyeClosed = $('#eyeClosed');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            eyeOpen.addClass('hidden');
            eyeClosed.removeClass('hidden');
        } else {
            passwordInput.attr('type', 'password');
            eyeClosed.addClass('hidden');
            eyeOpen.removeClass('hidden');
        }
    });
});