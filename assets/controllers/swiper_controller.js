import { Controller } from "@hotwired/stimulus"
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Connects to data-controller="swiper"
/* stimulusFetch: 'lazy' */
export default class SwiperController extends Controller {
  async connect() {
    const element = this.element;
    const datasetOptions = {};
    Object.entries(element.dataset).forEach(([key, value]) => {
      try {
        datasetOptions[key] = JSON.parse(value);
      } catch {
        datasetOptions[key] = value;
      }
    });

    new Swiper(element, {
      modules: [Navigation, Pagination, Autoplay],
      ...datasetOptions,
    });
  }

  disconnect() {
    this.element?.swiper?.destroy(true, true);
  }
}