import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/filament**/*.blade.php',
        './resources/views/livewire**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
}
