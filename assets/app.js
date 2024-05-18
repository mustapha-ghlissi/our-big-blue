/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import $ from 'jquery'
import 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min'
import 'bootstrap-datepicker/dist/locales/bootstrap-datepicker.fr.min'
import Tagify from '@yaireo/tagify'
import 'slick-carousel';

import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'
import '@yaireo/tagify/dist/tagify.css'
import 'bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css'
import  './styles/app.css'

$(document).ready(function () {

  initTagger()
  initDatePicker()

  const regexId = /^Form_fields_\d+_type$/
  $(document).on('change', '[id$=_type]', function () {
    const values = ['select', 'radio', 'checkbox']
    const $this = $(this)
    const id = $this.attr('id')
    const val = $this.val()
    const matchedId = regexId.test(id)
    const parent = $this.closest('.form-widget-compound > div')

    if (matchedId && values.includes(val)) {
      $(`#${parent.attr('id')}_possibleOptions`).closest('.form-group').removeClass('d-none')
      $(`#${parent.attr('id')}_multiple`).closest('.form-group').removeClass('d-none')
      initTagger(`${parent.attr('id')}_possibleOptions`)
    } else {
      $(`#${parent.attr('id')}_possibleOptions`).closest('.form-group').addClass('d-none')
      $(`#${parent.attr('id')}_multiple`).closest('.form-group').addClass('d-none')
    }
  })


  const modalImages = document.querySelector('[id^="modal-images-"]')
  if (modalImages) {
    modalImages.addEventListener('shown.bs.modal', event => {
      $('.slick').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true
      });
    })
  }



  function initTagger (id = null) {
    const tagsElement = id ? document.getElementById(id) : document.querySelector('input[data-tags]')

    if (tagsElement) {
      var tagify = new Tagify(tagsElement, {
        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
      })
    }
  }

  const myModalEl = document.getElementById('modal-filters')
  if (myModalEl) {
    myModalEl.addEventListener('shown.bs.modal', event => {
      initDatePicker()
    })
  }

  function initDatePicker() {
    const datepickerElement = $('.datepicker');
    if(datepickerElement.length) {
      datepickerElement.datepicker({
        format: 'dd/mm/yyyy',
        language: 'fr',
        todayHighlight: true,
        autoclose: true,
      })
    }
  }
})