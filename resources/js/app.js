import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import Precognition from "laravel-precognition-alpine";
import Swal from "sweetalert2";

window.Alpine = Alpine;

window.popup = {
    success(text, callback) {
        Swal.fire({
            title: "成功",
            text,
            icon: "success",
        }).then(callback);
    },
    info(text) {
        Swal.fire({
            title: "提示",
            text,
            icon: "info",
        });
    },
    error(text) {
        Swal.fire({
            title: "錯誤",
            text,
            icon: "error",
        });
    },
    confirm(text, callback) {
        Swal.fire({
            title: text,
            showDenyButton: true,
            confirmButtonText: "確定",
            confirmButtonColor: "#1E40AF",
            denyButtonText: "取消",
            denyButtonColor: "#EF4444",
        }).then(callback);
    },
};

Alpine.plugin(Precognition);
Alpine.plugin(collapse);

Alpine.start();
