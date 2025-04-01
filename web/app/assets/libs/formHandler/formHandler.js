function FormHandler(form, onSubmit) {

    var $form = form,
        formItems = $(form).find('input.form-control, textarea.form-control'),
        invalidClass = 'invalid',
        validClass = 'valid',
        invalidSelector = '.' + invalidClass,
        errorClass = 'form-error',
        successClass = 'form-success',
        errorArrow = 'form-error-arrow',
        errorBord = 'form-error-border',
        errorSelectorBorder = '.' + errorBord,
        errorSelector = '.' + errorClass,
        erroeSelectorArr = '.' + errorArrow,
        errorEmage = 'error-image',
        successImage = 'success-image',
        erroeSelectorImg = '.' + errorEmage,
        nextButton = $(form).find('#ndaConractDetailLink'),
        commonErrors = $form.prev();


    $form.on('submit', onFormSubmit);
    // $form.off('focus').on('focus', invalidSelector, hideError);
    $form.off('blur').on('blur', invalidSelector, showError);

    commonErrors.on('focusout', 'span', function () {
        $(this).remove();
    });


    $(document).ready(function () {
        if (window.history.length >= 2) {
            formItems.each(function () {
                if ($(this).val() != '' && $(this).attr('type') !== 'checkbox') {
                    formItems.each(function () {
                        validateField($(this));
                    });


                    var formItemsLength = $form.find('input.form-control, textarea.form-control').length,
                        formValidItemsLength = $form.find('input.form-control.valid, textarea.form-control.valid').length;


                    // if((formItemsLength - formValidItemsLength) === 0) {
                    //     $form.find("button").removeClass("off").removeAttr("disabled");
                    // } else {
                    //     $form.find("button").addClass("off").attr("disabled", "disabled");
                    // }
                    if ((formItemsLength - formValidItemsLength) === 0) {
                        $form.removeClass("notvalid");
                    } else {
                        $form.addClass("notvalid");
                    }

                    return false;
                }
            });
        }
    });

    formItems.on('input', function () {
        var formItemsLength = $form.find('input.form-control, textarea.form-control').length,
            formValidItemsLength = $form.find('input.form-control.valid, textarea.form-control.valid').length;


        if ((formItemsLength - formValidItemsLength) <= 1) {
            removeError($(this));
            validateField($(this));
            formItemsLength = $form.find('input.form-control, textarea.form-control').length;
            formValidItemsLength = $form.find('input.form-control.valid, textarea.form-control.valid').length;
        }

        if ((formItemsLength - formValidItemsLength) === 0) {
            $form.removeClass("notvalid");
        } else {
            $form.addClass("notvalid");
        }
    });


    formItems.on('change', function () {
        validateField($(this));

        var formItemsLength = $form.find('input.form-control, textarea.form-control').length,
            formValidItemsLength = $form.find('input.form-control.valid, textarea.form-control.valid').length;
        //
        // if((formItemsLength - formValidItemsLength) === 0) {
        //     $form.find("button").removeClass("off").removeAttr("disabled");
        // } else {
        //     $form.find("button").addClass("off").attr("disabled", "disabled");
        // }

        if ((formItemsLength - formValidItemsLength) === 0) {
            $form.removeClass("notvalid");
        } else {
            $form.addClass("notvalid");
        }

    });

    formItems.each(function () {
        if ($(this).attr('type') !== 'checkbox') {
            $(this).on('focusout', function () {
                validateField($(this));
            });
        }
    });


    // if (form.selector === '#newRegistrationForm') {
    //     $('#newReg_phone').off('blur').on('blur', function(){
    //         validateField($(this));
    //         if ($('form .phone-input .phone-inpt').hasClass('invalid')) {
    //             $('form .error_phone').show();
    //         } else {
    //             $('form .error_phone').hide();
    //         }
    //     });
    //     $('#newReg_frstName').off('blur').on('blur', function(){
    //         validateField($(this));
    //         if ($('form .first_name-label .first_name-input').hasClass('invalid')) {
    //             $('form .error_name').show();
    //         } else {
    //             $('form .error_name').hide();
    //         }
    //     });
    //     $('#newReg_email').off('blur').on('blur', function(){
    //         validateField($(this));
    //     });
    //     $('#newReg_pass').off('blur').on('blur', function(){
    //         validateField($(this));
    //         if ($('form .pass-input .pass-inpt').hasClass('invalid')) {
    //             $('form .error_pass').show();
    //         } else {
    //             $('form .error_pass').hide();
    //         }
    //     });
    //     $('#newReg_pass_rep').off('blur').on('blur', function(){
    //         validateField($(this));
    //     });
    // }
    //
    // if (form.selector === '#drawingOrderForm') {
    //     $('#newReg_name').off('blur').on('blur', function(){
    //         validateField($(this));
    //     });
    //     $('#newReg_orgName').off('blur').on('blur', function(){
    //         validateField($(this));
    //     });
    //     $('#newReg_requisites').off('blur').on('blur', function(){
    //         validateField($(this));
    //     });
    // }


    $('.step-btn_next').on('click', function () {
        $('#bannerForm .form-control').each(function() {
            validateField($(this));
        });
    });

    function onFormSubmit(e) {
        e.preventDefault();

        if (!validate($form)) {
            return;
        } else {
            onSubmit(getFormData());
        }
        ;

    };

    function validate($form) {
        var itemsLength = formItems.length,
            formValid = true,
            elem;

        for (var i = 0; i < itemsLength; i++) {
            elem = $(formItems[i]);

            if (elem.hasClass(invalidClass) || !validateField(elem)) {
                formValid = false;
            }
        }

        return formValid;

    };

    function validateField(field) {
        if (field.attr('type') === 'checkbox' && field.prop('required')) {
            if (!validator.checkCheckbox(field)) {
                addError(field, 'Обязательно');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;


        if (field.attr('type') === 'tel') {
            if (!validator.checkLengthPhone(field)) {
                addError(field, 'Максимум 30 знаков');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        if (field.attr('name') === 'first_name' || field.attr('name') === 'last_name') {
            if (!validator.checkLengthName(field)) {
                addError(field, 'Максимум 50 знаков');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        if (field.attr('type') === 'email') {
            if (!validator.checkLengthEmail(field)) {
                addError(field, 'Максимум 254 знаков');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        if (field.hasClass('max-length-counter')) {
            if (!validator.checkMaxLength(field)) {
                addError(field, 'Максимум 1800 знаков');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        // if(field.attr('type') === 'tel' && !field.prop('required')) {
        // 	debugger;
        // 	if(!validator.checkPhoneNoRequared(field)) {
        // 		addError(field, 'The entered data doesn’t contain any numbers');
        // 		return false;
        // 	} else {
        //        addSuccess(field);
        //    }
        // };


        if (field.attr('type') === 'tel' && field.prop('required')) {
            if (!validator.checkPhone(field)) {
                addError(field, 'Неверный телефонный номер');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;


        if (field.attr('type') === 'email') {
            if (!validator.checkEmail(field)) {
                addError(field, 'Неверное имя почты');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        // if(field.attr('name') === 'first-name') {
        // 	if(!validator.checkName(field)) {
        // 		addError(field, 'The entered data doesn’t contain name');
        // 		return false;
        // 	}
        // };
        //
        // if(field.attr('name') === 'last-name') {
        // 	if(!validator.checkName(field)) {
        // 		addError(field, 'The entered data doesn’t contain name');
        // 		return false;
        // 	} else {
        //        addSuccess(field);
        //    }
        // };

        if (field.attr('name') === 'pass') {
            if (!validator.checkPass(field)) {
                addError(field, 'Обратите внимание. Проверьте корректность введённых данных.');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        if (field.attr('name') === 'orgName') {
            if (!validator.checkOrgName(field)) {
                addError(field, 'Обратите внимание. Проверьте корректность введённых данных.');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        if (field.attr('name') === 'pass_again') {
            if (!validator.checkAgainPassw(field)) {
                addError(field, 'Обратите внимание. Проверьте корректность введённых данных.');
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;

        if (field.attr('name') === 'accept_personal') {
            if (!validator.checkPersonalData(field)) {
                return false;
            } else {
                addSuccess(field);
            }
        }
        ;
        if (field.prop('required') && field.attr('type') !== 'checkbox') {
            if (!validator.checkRequired(field)) {
                addError(field, 'Обязательно');
                return false;
            } else {
                addSuccess(field);
            }
        };


        if (field.hasClass('required-checkbox')) {
            if (!validator.checkCheckboxEmpty(field)) {
                addError(field, 'Обязательно');
                addError(field.closest('.custom-checkbox-wrap'), '');
                return false;
            } else {
                addSuccess(field.closest('.custom-checkbox-wrap'), '');
                addSuccess(field);
            }
        }




        if ($('form .pass-input .pass-inpt').hasClass('invalid')) {
            $('form .error_pass').show();
        } else {
            $('form .error_pass').hide();
        }

        if ($('form .first_name-label .first_name-input').hasClass('invalid')) {
            $('form .error_name').show();
        } else {
            $('form .error_name').hide();
        }

        if ($('form .phone-input .phone-inpt').hasClass('invalid')) {
            $('form .error_phone').show();
        } else {
            $('form .error_phone').hide();
        }

        return true;
    }

    function hideError() {
        $(this).siblings(errorSelector).hide();
        $(this).siblings(erroeSelectorArr).hide();
        $(this).siblings(erroeSelectorImg).hide();
        $(this).siblings(errorSelectorBorder).hide();
    };

    function showError() {
        $(this).siblings(errorSelector).show();
        $(this).siblings(erroeSelectorArr).show();
        $(this).siblings(erroeSelectorImg).show();
        $(this).siblings(errorSelectorBorder).show();
    };

    function addError(input, message) {
        var errorHtml = '<span class="' + errorClass + '">' + message + '</span>',
            error = $(errorHtml),
            label = input.parent();

        if (input.hasClass(validClass)) {
            input.removeClass(validClass);
        }
        if (!input.hasClass(invalidClass)) {
            input.addClass(invalidClass);
            label.append(error);
        }

    };

    function addSuccess(input) {
        var label = input.parent();
        if (input.hasClass(invalidClass)) {
            input.removeClass(invalidClass);
        }
        if (!input.hasClass(validClass)) {
            input.addClass(validClass);
            label.find('.form-error').remove();
            label.closest('.form-error').remove();
            input.closest('.wrp-input').find('.form-error').remove();
        }
    };


    function removeError(input) {
        input.removeClass(invalidClass);
        input.siblings(errorSelector).remove();
        input.siblings(erroeSelectorArr).remove();
        input.siblings(erroeSelectorImg).remove();
        input.siblings(errorSelectorBorder).remove();
    };

    function getFormData() {
        return $form.serializeArray();
    };

    function handleServerErrors(errorsObject) {
        var input,
            message;
        for (var error in errorsObject) {
            if (error === 'common') continue;

            input = $form.find('[name="' + error + '"]');
            message = errorsObject[error];
            addError(input, message);
        }
        ;
        if (errorsObject.common && errorsObject.common.length) {
            errorsObject.common.forEach(function (msg) {
                commonErrors.append('<span>' + msg + '</span>')
            });
        }
        ;
    };

    function clearForm() {
        formItems.each(function () {
            $(this).val('');
            commonErrors.empty();
            $form.find('.' + errorClass).remove()
        });
    };

    return {
        handleErrors: handleServerErrors,
        clearForm: clearForm
    };


};

var validator = {

    checkPersonalData: function () {
        var check = $('.accept_personal-label .icheckbox_minimal-orange');
        if (check.hasClass('checked')) {
            return true;
        }
        return false;
    },
    checkMaxLength: function (field) {
        var lengthField = field.val().length;
        if (lengthField <= 1800) {
            return true;
        } else {
            return false;
        }
    },
    checkLengthName: function (field) {
        var lengthField = field.val().length;
        if (lengthField <= 50) {
            return true;
        } else {
            return false;
        }
    },
    checkLengthPhone: function (field) {
        var lengthField = field.val().length;
        if (lengthField <= 30) {
            return true;
        } else {
            return false;
        }
    },
    checkLengthEmail: function (field) {
        var lengthField = field.val().length;
        if (lengthField <= 254) {
            return true;
        } else {
            return false;
        }
    },
    checkCheckbox: function (field) {
        if (field.prop('checked')) {
            return true;
        } else {
            return false;
        }
    },
    checkRequired: function (field) {
        return field.val().trim() ? true : false;
    },
    checkPhone: function (field) {
        // var regexp = /^[0-9()\-+ ]{1,14}$/,
        var regexp = /^[^0-9]*[0-9]{1,}[^]*$/,
            val = field.val();
        if (!val) {
            return true;
        }

        return regexp.test(val);
    },
    checkEmail: function (field) {
        var regexp = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]{1,}(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]{1,})*@(?:[a-z0-9]{2,}(?:[a-z0-9-]*[a-z0-9])?\.){1,}[a-z0-9]{2,}(?:[a-z0-9-]*[a-z0-9])?$/i,
            val = field.val();
        if (!val) {
            return true;
        }

        return regexp.test(val);
    },

    checkName: function (field) {
        var regexp = /^[а-яА-ЯёЁa-zA-Z]{0,30}$/,
            val = field.val();
        if (!val) {
            return true;
        }

        return regexp.test(val);
    },


    checkPass: function (field) {
        var regexp = /^[а-яА-ЯёЁa-zA-Z0-9_-]{5,12}$/,
            val = field.val();
        if (!val) {
            return true;
        }

        return regexp.test(val);
    },

    checkOrgName: function (field) {
        if (!this.isOrganizationChecked) {
            return true;
        }
        var regexp = /^[а-яА-ЯёЁa-zA-Z]{2,3}[\s]{1}['"]{1}[а-яА-ЯёЁa-zA-Z\s]+['"]{1}$/,
            val = field.val();
        if (!val) {
            return true;
        }

        return regexp.test(val);
    },

    checkAgainPassw: function () {
        var regexp = $('.pass-inpt').val(),
            val = $('.pass_again-inpt').val();
        if (val !== regexp) {
            return false;
        }
        return true;
    },

    checkPhoneNoRequared: function (field) {
        var regexp = /^[+]{1}[7]{1}[0-9()\-+ ]{0,14}$/,
            val = field.val();
        if (!val) {
            return true;
        }

        return regexp.test(val);
    },

    checkCheckboxEmpty: function (field) {
        if (field.prop("checked")) {
            return true
        } else {
            return false
        }
        // var flag = false;
        // if (field.prop("checked")) {
        //     flag = false;
        // } else {
        //     flag = true;
        // }
        // return flag;
    }
};


/**
 * document ready
 *  isset #bottomForm
 *      инициализируем все
 */

var apiServer = '/api';

formHandlerCallback();
function formHandlerCallback() {
    formHandler = new FormHandler($('#bottomForm'), function (formData) {
        let form = document.querySelector('#bottomForm');

        $(form).find('button[type="submit"]').on('click', function (event) {
            event.preventDefault();
        });

        let sendBtn = $(form).find('.submitButton');
        let preloaderBtn = $(form).find('.preloader-wrapper-btn');

        sendBtn.hide();
        preloaderBtn.show();

        var $inputs = $(form).find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = {};
        $($(form).serializeArray()).each(function(index, obj) {
            serializedData[obj.name] = obj.value;
        });

        $inputs.prop("disabled", true);


        request = $.ajax({
            type: 'POST',
            url: apiServer + '/request/call',
            contentType: 'application/json',
            dataType : 'json',
            data: JSON.stringify(serializedData)
        });

        request.done(function (response, textStatus, jqXHR){
            sendBtn.show();
            preloaderBtn.hide();
            $(form).trigger('reset');
            $('.modal').modal('close')
            $('#modalThanks').modal('open')
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
            sendBtn.show();
            preloaderBtn.hide();
            $('.modal').modal('close')
        });

        request.always(function () {
            $inputs.prop("disabled", false);
        });



    });
}

formHandlerOrderCallback();
function formHandlerOrderCallback() {
    formHandler = new FormHandler($('#orderForm'), function (formData) {
        let form = document.querySelector('#orderForm');

        $(form).find('button[type="submit"]').on('click', function (event) {
            event.preventDefault();
        });

        let sendBtn = $(form).find('.submitButton');
        let preloaderBtn = $(form).find('.preloader-wrapper-btn');

        sendBtn.hide();
        preloaderBtn.show();


        var $inputs = $(form).find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = {};
        $($(form).serializeArray()).each(function(index, obj) {
            serializedData[obj.name] = obj.value;
        });

        $inputs.prop("disabled", true);

        request = $.ajax({
            type: 'POST',
            url: apiServer + '/request/order',
            contentType: 'application/json',
            dataType : 'json',
            data: JSON.stringify(serializedData)
        });

        request.done(function (response, textStatus, jqXHR){
            sendBtn.show();
            preloaderBtn.hide();
            $(form).trigger('reset');
            $('.modal').modal('close')
            $('#modalThanks').modal('open')
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
            sendBtn.show();
            preloaderBtn.hide();
            $('.modal').modal('close')
        });

        request.always(function () {
            $inputs.prop("disabled", false);
        });

    });
}


formHandlerRideOrderCallback();
function formHandlerRideOrderCallback() {
    formHandler = new FormHandler($('#rideOrderForm'), function (formData) {
        let form = document.querySelector('#rideOrderForm');

        $(form).find('button[type="submit"]').on('click', function (event) {
            event.preventDefault();
        });

        let sendBtn = $(form).find('.submitButton');
        let preloaderBtn = $(form).find('.preloader-wrapper-btn');

        sendBtn.hide();
        preloaderBtn.show();


        var $inputs = $(form).find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = {};
        $($(form).serializeArray()).each(function(index, obj) {
            if(obj.value === 'true' || obj.value === 'false') {
                serializedData[obj.name] = (obj.value === 'true')
            } else {
                serializedData[obj.name] = obj.value;
            }
        });

        $inputs.prop("disabled", true);

        request = $.ajax({
            type: 'POST',
            url: apiServer + '/request/ride',
            contentType: 'application/json',
            dataType : 'json',
            data: JSON.stringify(serializedData)
        });

        request.done(function (response, textStatus, jqXHR){
            sendBtn.show();
            preloaderBtn.hide();
            $(form).trigger('reset');
            $('.modal').modal('close')
            $('#modalThanks').modal('open')
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
            sendBtn.show();
            preloaderBtn.hide();
            $('.modal').modal('close')
        });

        request.always(function () {
            $inputs.prop("disabled", false);
        });

    });
}


formHandlerServiceOrderCallback();
function formHandlerServiceOrderCallback() {
    formHandler = new FormHandler($('#serviceForm'), function (formData) {
        let form = document.querySelector('#serviceForm');

        $(form).find('button[type="submit"]').on('click', function (event) {
            event.preventDefault();
        });

        let sendBtn = $(form).find('.submitButton');
        let preloaderBtn = $(form).find('.preloader-wrapper-btn');

        sendBtn.hide();
        preloaderBtn.show();


        var $inputs = $(form).find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = {};
        $($(form).serializeArray()).each(function(index, obj) {
            serializedData[obj.name] = obj.value;
        });

        $inputs.prop("disabled", true);

        request = $.ajax({
            type: 'POST',
            url: apiServer + '/request/service',
            contentType: 'application/json',
            dataType : 'json',
            data: JSON.stringify(serializedData)
        });

        request.done(function (response, textStatus, jqXHR){
            sendBtn.show();
            preloaderBtn.hide();
            $(form).trigger('reset');
            $('.modal').modal('close')
            $('#modalThanks').modal('open')
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
            sendBtn.show();
            preloaderBtn.hide();
            $('.modal').modal('close')
        });

        request.always(function () {
            $inputs.prop("disabled", false);
        });

    });
}


