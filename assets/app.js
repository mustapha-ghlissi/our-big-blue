/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import $ from 'jquery';
import Tagify from '@yaireo/tagify'

import '@yaireo/tagify/dist/tagify.css';

$(document).ready(function () {

  initTagger();

  const regexId = /^Form_fields_\d+_type$/;
  $(document).on('change', '[id$=_type]', function () {
    const values = ['select', 'radio', 'checkbox']
    const $this = $(this);
    const id = $this.attr('id');
    const val = $this.val();
    const matchedId = regexId.test(id);
    const parent = $this.closest('.form-widget-compound > div');

    if (matchedId && values.includes(val)) {
      $(`#${parent.attr('id')}_possibleOptions`).closest('.form-group').removeClass('d-none');
      $(`#${parent.attr('id')}_multiple`).closest('.form-group').removeClass('d-none');
      initTagger(`${parent.attr('id')}_possibleOptions`);
    }
    else {
      $(`#${parent.attr('id')}_possibleOptions`).closest('.form-group').addClass('d-none');
      $(`#${parent.attr('id')}_multiple`).closest('.form-group').addClass('d-none');
    }
  })

  function initTagger (id = null) {
    const tagsElement = id ? document.getElementById(id) : document.querySelector('input[data-tags]');

    if (tagsElement) {
      var tagify = new Tagify(tagsElement, {
        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
      })
    }
  }
})