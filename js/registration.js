$( document ).ready(function() {
    function adjustIframeHeight() {
        var $body   = $('body'),
            $iframe = $body.data('iframe.fv');
        if ($iframe) {
            // Adjust the height of iframe
            $iframe.height($body.height());
        }
    }
    var signed = false;

    // $('body').on('custom:event', '#docusFrame', function(){console.log('aaaa');});

    

    //Init Step view
    $("#wizard").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onStepChanged: function(e, currentIndex, priorIndex) {
            // You don't need to care about it
            // It is for the specific demo
            adjustIframeHeight();

        },
        // Triggered when clicking the Previous/Next buttons
        onStepChanging: function(e, currentIndex, newIndex) {

            // var signed = false;
            var fv         = $('#wizard').data('formValidation'), // FormValidation instance
                // The current step container
                $container = $('#wizard').find('section[data-step="' + currentIndex +'"]');

            // Validate the container
            fv.validateContainer($container);
            document.getElementById("docusEvent").addEventListener('custom:event', function() {         
                signed = true;
            });

            

            var isValidStep = fv.isValidContainer($container);
            if (currentIndex == 3) {
                console.log(signed);
                if (!signed) return false;
                else return true;
            }
            if (isValidStep === false || isValidStep === null) {
                // Do not jump to the next step
                return false;
            }

            return true;
        },
        // Triggered when clicking the Finish button
        onFinishing: function(e, currentIndex) {
            var fv         = $('#wizard').data('formValidation'),
                $container = $('#wizard').find('section[data-step="' + currentIndex +'"]');

            // Validate the last step container
            fv.validateContainer($container);

            var isValidStep = fv.isValidContainer($container);
            if (isValidStep === false || isValidStep === null) {
                return false;
            }

            return true;
        },
        onFinished: function (event, currentIndex)
        {
            $('#new-client-form').submit();
        }
    })
    .formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        // This option will not ignore invisible fields which belong to inactive panels
        excluded: ':disabled',
        fields: {
            campaign_name: {
                validators: {
                    notEmpty: {
                        message: 'The Company Name is required'
                    },
                    stringLength: {
                        min: 6,
                        max: 100,
                        message: 'The Company Name must be more than 6 and less than 100 characters long'
                    },
                }
            },
            address: {
                validators: {
                    notEmpty: {
                        message: 'The Address is required'
                    },
                    stringLength: {
                        min: 6,
                        max: 255,
                        message: 'The Address must be more than 6 and less than 255 characters long'
                    },
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: 'The City is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 255,
                        message: 'The City must be more than 2 and less than 255 characters long'
                    },
                }
            },
            state: {
                validators: {
                    notEmpty: {
                        message: 'The State is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 255,
                        message: 'The State must be more than 2 and less than 255 characters long'
                    },
                }
            },
            abn: {
                validators: {
                    notEmpty: {
                        message: 'The ABN is required'
                    },
                    stringLength: {
                        min: 11,
                        max: 11,
                        message: 'The ABN be 11 characters long'
                    },
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: 'The phone number is required'
                    },
                    regexp: {
                        regexp: /^[0-9]{10,10}$/,
                        message: 'The phone consists from 10 numbers'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    },
                    different: {
                        field: 'username',
                        message: 'The password cannot be the same as username'
                    },
                    regexp: {
                        regexp: /^[0-9A-Za-z]{6,10}$/,
                        message: 'The password consists from letters, numbers and have 6-10 symbols'
                    }
                }
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'The confirm password is required'
                    },
                    identical: {
                        field: 'password',
                        message: 'The confirm password must be the same as original one'
                    }
                }
            },
            authorised_person: {
                validators: {
                    notEmpty: {
                        message: 'The signing terms and conditions is required'
                    },
                    stringLength: {
                        min: 3,
                        message: 'The authorised person name is less 3 symbols'
                    },
                    regexp: {
                        regexp: /^[A-Za-z ]$/,
                        message: 'The authorised person name can only consist of letters'
                    }
                }
            },
            name_on_card: {
                validators: {
                    notEmpty: {
                        message: 'The Name on Card is required'
                    },
                    stringLength: {
                        min: 3,
                        max: 100,
                        message: 'The Name on Card must be more than 3 and less than 100 characters long'
                    },
                }
            },
            credit_card_number: {
                validators: {
                    notEmpty: {
                        message: 'The Name on Card is required'
                    },
                    stringLength: {
                        min: 16,
                        max: 16,
                        message: 'The Credit Card Number must be 16 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The Credit Card Number can only consist of numbers'
                    }
                }
            },
            expires_mm: {
                validators: {
                    notEmpty: {
                        message: 'The Expires is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 2,
                        message: 'The Expires Number must be 2 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The Expires can only consist of numbers'
                    }
                }
            },
            expires_yy: {
                validators: {
                    notEmpty: {
                        message: 'The Expires is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 2,
                        message: 'The Expires Number must be 2 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The Expires can only consist of numbers'
                    }
                }
            },
            cvc: {
                validators: {
                    notEmpty: {
                        message: 'The CVC is required'
                    },
                    stringLength: {
                        min: 3,
                        max: 3,
                        message: 'The CVC must be 3 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The CVC can only consist of numbers'
                    }
                }
            },
            lorem1: {
                validators: {
                    notEmpty: {
                        message: ''
                    },
                }
            },
            lorem2: {
                validators: {
                    notEmpty: {
                        message: ''
                    },
                }
            },
            lorem3: {
                validators: {
                    notEmpty: {
                        message: ''
                    },
                }
            },
            lorem4: {
                validators: {
                    notEmpty: {
                        message: ''
                    },
                }
            },
            lorem5: {
                validators: {
                    notEmpty: {
                        message: ''
                    },
                }
            },
            lorem6: {
                validators: {
                    notEmpty: {
                        message: ''
                    },
                }
            },
        }
    });

$('#authorised_person').change(function(){
    var authorised_person = $(this).val();
    var regExpInput = new RegExp(this.getAttribute('pattern'));
    if (regExpInput.test(authorised_person)) {
        $('#submitDocus').attr("disabled", false);
    }    
});

$('#submitDocus').click(function(){

    var email_docus = $('#email').val();
    var authorised_person = $('#authorised_person').val();
    $('#docusPerson').hide();
    $('#submitDocus').hide();
    $('#docusPermit').hide();
    $('#tncText').hide();
    $('#docus').show();
    $.ajax({
        type: "POST",
        url: './app/api/docusign_embedded_sign_api.php',
        data: ({recipientName: authorised_person, recipientEmail: email_docus}),
        beforeSend: darkLoader,
        success: function(res) {
            $('#overlay').hide();
            $('#docusEvent').html('<iframe src="' + res + '" id="docusFrame" width="1000" height="450" style="border-width:0px;"></iframe>');
            console.log(window.parent.document.getElementById('docusFrame').parentNode);
        }
    });
});    

    function darkLoader() 
    {
        var docHeight = $(document).height();
        $("body").append("<div id='overlay'></div>");
        $("#overlay")
        .height(docHeight)
        .css({
            'opacity' : 0.4,
            'position': 'absolute',
            'top': 0,
            'left': 0,
            'background-color': 'black',
            'width': '100%',
            'z-index': 5000
        });
    }

});