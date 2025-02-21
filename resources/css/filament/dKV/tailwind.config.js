import preset from "../../../../vendor/filament/filament/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/DKV/**/*.php",
        "./resources/views/filament/d-k-v/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/awcodes/overlook/resources/**/*.blade.php",
        "./vendor/guava/filament-modal-relation-managers/resources/**/*.blade.php",
    ],
};
