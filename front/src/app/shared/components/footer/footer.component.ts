import { Component, Input, OnInit } from '@angular/core';
import * as moment from 'moment';
import { ScrollService } from '../../../core/services/scroll.service';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css']
})
export class FooterComponent implements OnInit {

  currentYear = moment().year();

  constructor(private scrollService: ScrollService) { }

  ngOnInit() {
  }

  scrollToElement(anchor: string) {
    this.scrollService.scrollToElement(anchor);
  }
}
