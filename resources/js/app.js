import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import Precognition from "laravel-precognition-alpine";
import Swal from "sweetalert2";

window.Alpine = Alpine;

window.popup = {
    success({ title = "成功", text, callback }) {
        Swal.fire({
            title,
            imageUrl: "/images/icons/success.svg",
            imageWidth: 40,
            text,
            confirmButtonText: "確定",
            confirmButtonColor: "#5768FF",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                image: "!my-0 !mt-6 !mx-6",
                htmlContainer: "!text-[#64748B] !text-sm !text-left",
                actions: "!w-full !px-6",
                confirmButton:
                    "!w-full !font-semibold !m-0 !rounded-xl !h-[50px]",
                container: "!bg-[#264573] !bg-opacity-50",
            },
        }).then(callback);
    },
    info({ title = "提示", text }) {
        Swal.fire({
            title,
            text,
            confirmButtonText: "確定",
            confirmButtonColor: "#5768FF",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                htmlContainer: "!text-[#64748B] !text-sm !text-left",
                actions: "!w-full !px-6",
                confirmButton:
                    "!w-full !font-semibold !m-0 !rounded-xl !h-[50px]",
                container: "!bg-[#264573] !bg-opacity-50",
            },
        });
    },
    error({ title = "錯誤", text }) {
        Swal.fire({
            title,
            imageUrl: "/images/icons/info.svg",
            text,
            confirmButtonText: "確定",
            confirmButtonColor: "#5768FF",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                image: "!my-0 !mt-6 !mx-6",
                htmlContainer: "!text-[#64748B] !text-sm !text-left",
                actions: "!w-full !px-6",
                confirmButton:
                    "!w-full !font-semibold !m-0 !rounded-xl !h-[50px]",
                container: "!bg-[#264573] !bg-opacity-50",
            },
        });
    },
    confirm({ title, text, confirmButtonText = "確定", callback }) {
        Swal.fire({
            title,
            imageUrl: "/images/icons/info.svg",
            text,
            confirmButtonText,
            confirmButtonColor: "#5768FF",
            customClass: {
                popup: "!rounded-2xl !mt-6",
                title: "!text-left !text-xl",
                image: "!my-0 !mt-6 !mx-6",
                htmlContainer: "!text-[#64748B] !text-sm !text-left",
                actions: "!w-full !px-6",
                confirmButton:
                    "!w-full !font-semibold !m-0 !rounded-xl !h-[50px]",
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
