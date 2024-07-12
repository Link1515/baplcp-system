import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import Precognition from "laravel-precognition-alpine";
import Swal from "sweetalert2";

window.Alpine = Alpine;

window.popup = {
    success(text) {
        Swal.fire({
            title: "成功",
            text,
            icon: "success",
        });
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
};

Alpine.plugin(Precognition);
Alpine.plugin(collapse);

Alpine.start();
