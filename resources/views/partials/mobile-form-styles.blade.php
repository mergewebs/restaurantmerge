<style>
    /* Layered !important rules beat the page-specific small field sizes. */
    @layer mobile-forms {
        @media (max-width: 767px), (hover: none) and (pointer: coarse) {
            /* iOS zooms focused fields below 16px; leave pinch-to-zoom enabled. */
            input:not([type="checkbox"]):not([type="radio"]):not([type="range"]):not([type="color"]):not([type="hidden"]):not([type="button"]):not([type="submit"]):not([type="reset"]):not([type="image"]),
            select,
            textarea,
            [contenteditable="true"],
            [contenteditable=""] {
                font-size: max(16px, 1em) !important;
            }
        }
    }
</style>
