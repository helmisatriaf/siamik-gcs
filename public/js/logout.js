$(document).ready(() => {
    $("body").on("click", "#log-out", function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Are you sure want to exit?",
            // icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonColor: "#00b527",
            confirmButtonText: "Continue",
            imageUrl: '/images/confuse.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'rounded-swal',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/logout`,
                    type: "GET",
                    cache: false,
                    // data: {
                    //     id: value,
                    //     _token: token,
                    // },
                })
                    .then((res) => {
                        if (res.success) {
                            window.location.href = "/";
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                                footer: '<a href="#">Why do I have this issue?</a>',
                            });
                        }
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="#">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });
});
