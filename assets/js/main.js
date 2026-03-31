'use strict';

// Theme JavaScript entry point.
(function () {
  const loader = document.getElementById('page-loader');

  if (!loader) {
    return;
  }

  const hideLoader = () => {
    loader.classList.add('opacity-0', 'pointer-events-none');
    window.setTimeout(() => {
      loader.classList.add('hidden');
    }, 300);
  };

  if (document.readyState === 'complete') {
    hideLoader();
  } else {
    window.addEventListener('load', hideLoader, { once: true });
  }

  window.addEventListener('pageshow', hideLoader, { once: true });
})();

(function () {
  const textInputClasses = [
    'block',
    'w-full',
    'rounded-lg',
    'border',
    'border-slate-300',
    'bg-slate-50',
    'p-2.5',
    'text-sm',
    'text-slate-900',
    'focus:border-blue-500',
    'focus:ring-blue-500',
  ];

  const selectClasses = [
    'block',
    'w-full',
    'rounded-lg',
    'border',
    'border-slate-300',
    'bg-slate-50',
    'p-2.5',
    'text-sm',
    'text-slate-900',
    'focus:border-blue-500',
    'focus:ring-blue-500',
  ];

  const checkboxRadioClasses = [
    'h-4',
    'w-4',
    'rounded',
    'border-slate-300',
    'text-blue-600',
    'focus:ring-blue-500',
  ];

  const buttonClasses = [
    'inline-flex',
    'items-center',
    'justify-center',
    'rounded-lg',
    'bg-blue-700',
    'px-5',
    'py-2.5',
    'text-sm',
    'font-medium',
    'text-white',
    'hover:bg-blue-800',
    'focus:outline-none',
    'focus:ring-4',
    'focus:ring-blue-300',
  ];

  function applyClasses(element, classes) {
    if (!element) {
      return;
    }
    element.classList.add(...classes);
  }

  function styleFormElements(root = document) {
    const labels = root.querySelectorAll('form label:not(.fb-form-styled)');
    labels.forEach((label) => {
      label.classList.add('fb-form-styled', 'mb-2', 'block', 'text-sm', 'font-medium', 'text-slate-900');
    });

    const textInputs = root.querySelectorAll(
      "form input:not([type='checkbox']):not([type='radio']):not([type='submit']):not([type='button']):not([type='reset']):not([type='hidden']):not(.fb-form-styled), form textarea:not(.fb-form-styled)"
    );
    textInputs.forEach((field) => {
      field.classList.add('fb-form-styled');
      applyClasses(field, textInputClasses);
    });

    const selects = root.querySelectorAll('form select:not(.fb-form-styled)');
    selects.forEach((field) => {
      field.classList.add('fb-form-styled');
      applyClasses(field, selectClasses);
    });

    const checks = root.querySelectorAll("form input[type='checkbox']:not(.fb-form-styled), form input[type='radio']:not(.fb-form-styled)");
    checks.forEach((field) => {
      field.classList.add('fb-form-styled');
      applyClasses(field, checkboxRadioClasses);
    });

    const buttons = root.querySelectorAll(
      "form button:not(.fb-form-styled), form input[type='submit']:not(.fb-form-styled), form input[type='button']:not(.fb-form-styled), form input[type='reset']:not(.fb-form-styled)"
    );
    buttons.forEach((button) => {
      button.classList.add('fb-form-styled');
      applyClasses(button, buttonClasses);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    styleFormElements(document);
  });

  if (window.jQuery) {
    window.jQuery(document.body).on(
      'updated_checkout updated_wc_div wc_fragments_loaded wc_fragments_refreshed',
      () => styleFormElements(document)
    );
  }
})();
