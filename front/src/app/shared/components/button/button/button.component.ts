import { Component, Input, OnInit } from '@angular/core';
import { Button } from "../button";

@Component({
  selector: 'app-button',
  templateUrl: './button.component.html'
})
export class ButtonComponent
  extends Button
  implements OnInit {

  @Input() isLoading: boolean = false;

  constructor() {
    super();
  }

  ngOnInit() {
    this.animation = this.hiddenIcon != undefined ? 'fade animated' : '';

    this.classes = [
      this.color, this.size, this.animation, this.attachment, this.behaviour,
    ];
  }
}