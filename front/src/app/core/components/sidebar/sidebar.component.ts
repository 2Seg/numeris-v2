import { Component, OnInit } from '@angular/core';
import { Observable } from "rxjs";
import { AuthService } from "../../auth/auth.service";
import { ScrollService } from "../../services/scroll.service";
import { Router } from "@angular/router";

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {

  isLoggedIn$: Observable<boolean>;

  constructor(
    public router: Router,
    private authService : AuthService,
    private scrollService: ScrollService
  ) { }

  ngOnInit() {
    this.isLoggedIn$ = this.authService.isLoggedIn;
  }

  scrollToElement(anchor: string) {
    this.scrollService.scrollToElement(anchor);
  }

  // This is to avoid red underlining from the IDE
  open(): void {}
  close(): void {}
  toggle(): void {}
}