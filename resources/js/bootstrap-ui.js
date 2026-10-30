// Bootstrap 5 for pages that use layouts/bootstrap.blade.php.
// Kept separate from app.js / app.css (Tailwind) so the two CSS systems never load on the same page.
import 'bootstrap/dist/css/bootstrap.min.css';

// The bundle build includes Popper, which dropdowns, tooltips and popovers need.
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.bootstrap = bootstrap;
