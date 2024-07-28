import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import Precognition from "laravel-precognition-alpine";
import Swal from "sweetalert2";

window.Alpine = Alpine;

window.popup = {
    success({
        title = "成功",
        text,
        confirmButtonText = "我知道了",
        callback,
    }) {
        Swal.fire({
            title,
            imageUrl: "/images/icons/success.svg",
            imageWidth: 40,
            text,
            confirmButtonText,
            confirmButtonColor: "#5768FF",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                image: "!my-0 !mt-6 !mx-6",
                htmlContainer: "!text-[#64748B] !text-[15px] !text-left",
                actions: "!w-full !px-6",
                confirmButton: '!btn-primary !m-0',
                container: "!bg-[#264573] !bg-opacity-50",
            },
        }).then(callback);
    },
    info({ title = "提示", text }) {
        Swal.fire({
            title,
            text,
            confirmButtonText: "我知道了",
            confirmButtonColor: "#5768FF",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                htmlContainer: "!text-[#64748B] !text-[15px] !text-left",
                actions: "!w-full !px-6",
                confirmButton: "!btn-primary !m-0",
                container: "!bg-[#264573] !bg-opacity-50",
            },
        });
    },
    error({ title = "錯誤", text }) {
        Swal.fire({
            title,
            imageUrl: "/images/icons/warning.svg",
            text,
            confirmButtonText: "我知道了",
            confirmButtonColor: "#5768FF",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                image: "!my-0 !mt-6 !mx-6",
                htmlContainer: "!text-[#64748B] !text-[15px] !text-left",
                actions: "!w-full !px-6",
                confirmButton: "!btn-primary !m-0",
                container: "!bg-[#264573] !bg-opacity-50",
            },
        });
    },
    confirm({
        title,
        text,
        confirmButtonText = "我知道了",
        cancelButtonText = "我再想想",
        callback,
    }) {
        Swal.fire({
            title,
            imageUrl: "/images/icons/info.svg",
            text,
            confirmButtonText,
            confirmButtonColor: "#5768FF",
            showCancelButton: "true",
            cancelButtonText,
            cancelButtonColor: "#ffffff",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                image: "!my-0 !mt-6 !mx-6",
                htmlContainer: "!text-[#64748B] !text-[15px] !text-left",
                actions: "!w-full !px-6 !grid !grid-cols-2 !gap-2",
                confirmButton: "!btn-primary !m-0 !order-1",
                cancelButton: "!btn-outline-primary !m-0",
                container: "!bg-[#264573] !bg-opacity-50",
            },
        }).then(callback);
    },
};

window.requestDelete = function (text, url, redirect = "/") {
    window.popup.confirm({
        text,
        callback: (result) => {
            if (result.isDenied || result.isDismissed) return;
            window.axios.delete(url).then(({ status }) => {
                if (status === 200) {
                    window.popup.success({
                        text: "成功",
                        callback: () => {
                            window.location.href = redirect;
                        },
                    });
                }
            });
        },
    });
};

Alpine.plugin(Precognition);
Alpine.plugin(collapse);

Alpine.start();
