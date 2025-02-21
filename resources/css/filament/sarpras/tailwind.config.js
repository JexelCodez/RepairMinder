import preset from "../../../../vendor/filament/filament/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/Sarpras/**/*.php",
        "./resources/views/filament/sarpras/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/awcodes/overlook/resources/**/*.blade.php",
        "./vendor/guava/filament-modal-relation-managers/resources/**/*.blade.php",
    ],
};
