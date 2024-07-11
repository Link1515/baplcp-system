import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import Precognition from "laravel-precognition-alpine";

window.Alpine = Alpine;

Alpine.plugin(Precognition);
Alpine.plugin(collapse);

Alpine.start();
