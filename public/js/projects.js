$(document).ready(function () {
    $("body").on("click", "#delete-student", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to inactive " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, inactive!",
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(value);
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                        "Access-Control-Allow-Origin": "*",
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                        "Access-Control-Allow-Origin": "*",
                    },
                    url: `/superadmin/student/${value}`,
                    type: "PATCH",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Inactive!",
                            `${name} has been inactive.`,
                            "success"
                        );

                        //   setTimeout(() => {
                        //       window.location.href = '/admin/list'
                        //   }, 1500)

                        $(`#index_student_${value}`).remove();
                    })
                    .catch((err) => {
                        console.log(err);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            // text: err,
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#delete-user", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to delete " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("masuk");
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/superadmin/users/delete/${value}`,
                    type: "get",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Deleted!",
                            `${name} has been deleted.`,
                            "success"
                        );

                        //   setTimeout(() => {
                        //       window.location.href = '/admin/user'
                        //   }, 1500)

                        $(`#index_user_${value}`).remove();
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#delete-teacher", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        const role = $("meta[name='role']").attr("content"); // Ambil role dari meta tag

        Swal.fire({
            title: "Are you sure want to inactive " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, inactive!",
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("masuk");
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/${role}/teachers/deactivated/${value}`,
                    type: "put",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Inactive!",
                            `${name} has been inactive.`,
                            "success"
                        ).then(() => {
                            window.location.reload(); // Reload halaman setelah berhasil
                        });
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#active-teacher", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        const token = $("meta[name='csrf-token']").attr("content");
        const role = $("meta[name='role']").attr("content"); // Ambil role dari meta tag

        Swal.fire({
            title: "Are you sure you want to activate " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, activate!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    url: `/${role}/teachers/activated/${value}`, // Gunakan role dari meta tag
                    type: "PUT",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .done((res) => {
                        Swal.fire(
                            "Activated!",
                            `${name} has been activated.`,
                            "success"
                        ).then(() => {
                            window.location.reload(); // Reload halaman setelah berhasil
                        });
                    })
                    .fail((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#active-student", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to activate " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, activate it !",
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
                    url: `/admin/student/activate/${value}`,
                    type: "PATCH",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);

                        if (!res.success) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: res.text,
                                footer: '<a href="">Why do I have this issue?</a>',
                            });
                        } else {
                            Swal.fire(
                                "Activate!",
                                `${name} has been activate.`,
                                "success"
                            );

                            $(`#index_student_${value}`).remove();
                        }
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });
});
