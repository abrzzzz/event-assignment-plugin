import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client'
import "./css/app.css";
import axios from 'axios';



createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.tsx', {
      eager: true
    })
    return pages[`./Pages/${name}.tsx`]
  },
  setup({ el, App, props }) {
    createRoot(el).render(
      <div>
          <App {...props} />
      </div>
    )
  },
})