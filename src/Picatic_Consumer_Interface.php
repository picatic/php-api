<?php

/**
 * Do you need to access the PicaticAPI instance, you need this!
 */

interface Picatic_Consumer_Interface {
  /**
   * Set the instance of the PicaticAPI
   * @param PicaticAPI $picaticApi instance of the PicaticAPI
   */
  public function setPicaticApi($picaticApi);

  /**
   * Get the instance of the PicaticAPI
   * @return PicaticAPI instance
   */
  public function getPicaticApi();
}
