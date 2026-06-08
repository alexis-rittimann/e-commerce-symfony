import { Controller } from '@hotwired/stimulus';

/*
 * Stepper de quantité : les boutons +/- font varier le champ <input type="number">.
 * stepDown() s'arrête au min (0) défini sur l'input.
 */
export default class extends Controller {
    static targets = ['input'];

    decrease() {
        this.inputTarget.stepDown();
    }

    increase() {
        this.inputTarget.stepUp();
    }
}
