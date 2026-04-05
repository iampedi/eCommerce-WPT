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
    'border-gray-600',
    'bg-gray-700',
    'p-2.5',
    'text-sm',
    'text-white',
    'placeholder-gray-400',
    'focus:border-blue-500',
    'focus:ring-blue-500',
  ];

  const selectClasses = [
    'block',
    'w-full',
    'rounded-lg',
    'border',
    'border-gray-600',
    'bg-gray-700',
    'p-2.5',
    'text-sm',
    'text-white',
    'focus:border-blue-500',
    'focus:ring-blue-500',
  ];

  const checkboxRadioClasses = [
    'h-4',
    'w-4',
    'rounded',
    'border-gray-600',
    'bg-gray-700',
    'text-blue-600',
    'focus:ring-2',
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
    const formScope = 'form:not([data-form-style="manual"])';

    const labels = root.querySelectorAll(`${formScope} label:not(.fb-form-styled)`);
    labels.forEach((label) => {
      label.classList.add('fb-form-styled', 'mb-2', 'block', 'text-sm', 'font-medium', 'text-white');
    });

    const textInputs = root.querySelectorAll(
      `${formScope} input:not([type='checkbox']):not([type='radio']):not([type='range']):not([type='submit']):not([type='button']):not([type='reset']):not([type='hidden']):not(.fb-form-styled), ${formScope} textarea:not(.fb-form-styled)`
    );
    textInputs.forEach((field) => {
      field.classList.add('fb-form-styled');
      applyClasses(field, textInputClasses);
    });

    const selects = root.querySelectorAll(`${formScope} select:not(.fb-form-styled)`);
    selects.forEach((field) => {
      field.classList.add('fb-form-styled');
      applyClasses(field, selectClasses);
    });

    const checks = root.querySelectorAll(`${formScope} input[type='checkbox']:not(.fb-form-styled), ${formScope} input[type='radio']:not(.fb-form-styled)`);
    checks.forEach((field) => {
      field.classList.add('fb-form-styled');
      applyClasses(field, checkboxRadioClasses);
    });

    const buttons = root.querySelectorAll(
      `${formScope} button:not(.fb-form-styled), ${formScope} input[type='submit']:not(.fb-form-styled), ${formScope} input[type='button']:not(.fb-form-styled), ${formScope} input[type='reset']:not(.fb-form-styled)`
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

(function () {
  function formatRangeValue(value, decimals, unit = '') {
    const formatted = Number(value).toFixed(decimals);
    return unit ? `${formatted} ${unit}` : formatted;
  }

  function submitForm(form) {
    if (!form) {
      return;
    }

    if (typeof form.requestSubmit === 'function') {
      form.requestSubmit();
      return;
    }

    form.submit();
  }

  function bindRangeFilters(root = document) {
    const rangeFilters = root.querySelectorAll('[data-range-filter]');
    rangeFilters.forEach((filter) => {
      if (filter.dataset.rangeBound === '1') {
        return;
      }

      filter.dataset.rangeBound = '1';

      const minRange = filter.querySelector('input[type="range"][data-range-role="min"]');
      const maxRange = filter.querySelector('input[type="range"][data-range-role="max"]');
      const minHidden = filter.querySelector('input[type="hidden"][data-range-hidden="min"]');
      const maxHidden = filter.querySelector('input[type="hidden"][data-range-hidden="max"]');
      const activeHidden = filter.querySelector('input[type="hidden"][data-range-hidden="active"]');
      const minLabel = filter.querySelector('[data-range-min-label]');
      const maxLabel = filter.querySelector('[data-range-max-label]');
      const activeTrack = filter.querySelector('[data-range-active-track]');
      const parentForm = filter.closest('form');
      const decimals = Number(filter.dataset.rangeDecimals || '2');
      const unit = String(filter.dataset.rangeUnit || '').trim();
      const validValues = String(filter.dataset.rangeValues || '')
        .split(',')
        .map((value) => Number(value))
        .filter((value) => Number.isFinite(value))
        .sort((left, right) => left - right);

      if (!minRange || !maxRange || !minHidden || !maxHidden) {
        return;
      }

      const pickClosestValue = (target, values) => {
        if (!values.length) {
          return target;
        }

        let closest = values[0];
        let smallestDistance = Math.abs(values[0] - target);

        values.forEach((value) => {
          const distance = Math.abs(value - target);
          if (distance < smallestDistance) {
            closest = value;
            smallestDistance = distance;
          }
        });

        return closest;
      };

      const pickClosestAtMost = (target, upperBound, values) => {
        const subset = values.filter((value) => value <= upperBound + 1e-9);
        if (!subset.length) {
          return upperBound;
        }

        return pickClosestValue(target, subset);
      };

      const pickClosestAtLeast = (target, lowerBound, values) => {
        const subset = values.filter((value) => value >= lowerBound - 1e-9);
        if (!subset.length) {
          return lowerBound;
        }

        return pickClosestValue(target, subset);
      };

      const syncRangeValues = (changedRole) => {
        const lowerBound = Number(minRange.min);
        const upperBound = Number(minRange.max);
        const span = upperBound - lowerBound;
        let minValue = Number(minRange.value);
        let maxValue = Number(maxRange.value);
        const minLimitMax = Number(minRange.dataset.rangeLimitMax || minRange.max);
        const maxLimitMin = Number(maxRange.dataset.rangeLimitMin || maxRange.min);

        if (Number.isFinite(minLimitMax)) {
          minValue = Math.min(minValue, minLimitMax);
        }

        if (Number.isFinite(maxLimitMin)) {
          maxValue = Math.max(maxValue, maxLimitMin);
        }

        if (validValues.length > 0) {
          if (changedRole === 'max') {
            maxValue = pickClosestAtLeast(maxValue, minValue, validValues);
            minValue = pickClosestAtMost(minValue, maxValue, validValues);
          } else {
            minValue = pickClosestAtMost(minValue, maxValue, validValues);
            maxValue = pickClosestAtLeast(maxValue, minValue, validValues);
          }
        }

        if (minValue > maxValue) {
          if (changedRole === 'max') {
            minValue = maxValue;
          } else {
            maxValue = minValue;
          }
        }

        minRange.value = String(minValue);
        maxRange.value = String(maxValue);
        minHidden.value = String(minValue);
        maxHidden.value = String(maxValue);

        if (activeTrack && span > 0) {
          const minPercent = ((minValue - lowerBound) / span) * 100;
          const maxPercent = ((maxValue - lowerBound) / span) * 100;
          activeTrack.style.left = `${minPercent}%`;
          activeTrack.style.width = `${Math.max(0, maxPercent - minPercent)}%`;
        }

        if (maxValue - minValue <= Number(minRange.step || '0.01')) {
          if (changedRole === 'min') {
            minRange.style.zIndex = '40';
            maxRange.style.zIndex = '30';
          } else {
            minRange.style.zIndex = '30';
            maxRange.style.zIndex = '40';
          }
        } else {
          minRange.style.zIndex = '30';
          maxRange.style.zIndex = '40';
        }

        if (minLabel) {
          minLabel.textContent = formatRangeValue(minValue, decimals, unit);
        }
        if (maxLabel) {
          maxLabel.textContent = formatRangeValue(maxValue, decimals, unit);
        }
      };

      minRange.addEventListener('input', () => syncRangeValues('min'));
      maxRange.addEventListener('input', () => syncRangeValues('max'));

      minRange.addEventListener('change', () => {
        syncRangeValues('min');
        if (activeHidden) {
          activeHidden.value = '1';
        }
        if (parentForm && parentForm.dataset.autoSubmit === '1') {
          submitForm(parentForm);
        }
      });

      maxRange.addEventListener('change', () => {
        syncRangeValues('max');
        if (activeHidden) {
          activeHidden.value = '1';
        }
        if (parentForm && parentForm.dataset.autoSubmit === '1') {
          submitForm(parentForm);
        }
      });

      syncRangeValues('min');
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    bindRangeFilters(document);
  });
})();

(function () {
  function submitForm(form) {
    if (!form) {
      return;
    }

    if (typeof form.requestSubmit === 'function') {
      form.requestSubmit();
      return;
    }

    form.submit();
  }

  function bindArchiveAutoSubmit(root = document) {
    const forms = root.querySelectorAll('form[data-auto-submit="1"]');
    forms.forEach((form) => {
      if (form.dataset.autoSubmitBound === '1') {
        return;
      }

      form.dataset.autoSubmitBound = '1';
      const watchedFields = form.querySelectorAll("select, input[type='checkbox'], input[type='radio'], input[type='number']");

      watchedFields.forEach((field) => {
        field.addEventListener('change', () => submitForm(form));
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    bindArchiveAutoSubmit(document);
  });
})();

(function () {
  function hasActiveArchiveFilterQuery() {
    const params = new URLSearchParams(window.location.search);
    const directKeys = new Set([
      'filter_color',
      'filter_cut',
      'filter_matrix',
      'filter_shape',
      'min_price',
      'max_price',
      'min_width',
      'max_width',
      'min_height',
      'max_height',
      'price_active',
      'width_active',
      'height_active',
      'available',
    ]);

    for (const key of params.keys()) {
      if (key.startsWith('pf_') || directKeys.has(key)) {
        return true;
      }
    }

    return false;
  }

  function bindArchiveFilterPanel(root = document) {
    const panel = root.querySelector('[data-archive-filter-panel]');
    if (!panel || panel.dataset.panelBound === '1') {
      return;
    }

    panel.dataset.panelBound = '1';

    const toggles = root.querySelectorAll('[data-archive-filter-toggle]');
    const closeButtons = root.querySelectorAll('[data-filter-close]');
    const filterForm = panel.querySelector('form[data-archive-filter-form]');
    const fallbackResetUrl = filterForm ? filterForm.getAttribute('data-reset-url') || filterForm.getAttribute('action') : window.location.pathname;

    const setOpenState = (isOpen) => {
      panel.classList.toggle('is-open', isOpen);
      panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
      toggles.forEach((toggle) => {
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });
    };

    // Requested behavior: filters are hidden by default.
    setOpenState(false);

    toggles.forEach((toggle) => {
      toggle.addEventListener('click', () => {
        setOpenState(!panel.classList.contains('is-open'));
      });
    });

    closeButtons.forEach((button) => {
      button.addEventListener('click', (event) => {
        event.preventDefault();
        setOpenState(false);

        const resetUrl = button.getAttribute('data-reset-url') || fallbackResetUrl || window.location.pathname;
        if (hasActiveArchiveFilterQuery()) {
          window.location.href = resetUrl;
        }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    bindArchiveFilterPanel(document);
  });
})();
