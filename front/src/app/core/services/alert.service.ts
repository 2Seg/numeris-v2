import { Injectable } from '@angular/core';
import { NavigationStart, Router } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { Alert, AlertType } from '../classes/alert';

@Injectable({
  providedIn: 'root'
})
export class AlertService {

  private subject = new Subject<Alert>();
  private keepAfterRouteChange = false;

  constructor(private router: Router) {
    // clear alert messages on route change unless 'keepAfterRouteChange' flag is true
    router.events.subscribe(event => {
      if (event instanceof NavigationStart) {
        if (this.keepAfterRouteChange) {
          // only keep for a single route change
          this.keepAfterRouteChange = false;
        } else {
          // clear alert messages
          this.clear();
        }
      }
    });
  }

  getAlert(): Observable<any> {
    return this.subject.asObservable();
  }

  alert(type: AlertType, content: string[], title: string | null, keepAfterRouteChange, target: string, icon: boolean = true) {
    this.clear();
    this.keepAfterRouteChange = keepAfterRouteChange;
    this.subject.next(<Alert> { type: type, content: content, title: title, target: target, icon: icon });
  }

  success(content: string[], title: string | null = null, keepAfterRouteChange = false, target: string = 'main') {
    this.alert(AlertType.Success, content, title, keepAfterRouteChange, target);
  }

  info(content: string[], title: string | null = null, keepAfterRouteChange = false, target: string = 'main') {
    this.alert(AlertType.Info, content, title, keepAfterRouteChange, target);
  }

  warning(content: string[], title: string | null = null, keepAfterRouteChange = false, target: string = 'main') {
    this.alert(AlertType.Warning, content, title, keepAfterRouteChange, target);
  }

  error(content: string[], title: string | null = null, keepAfterRouteChange = false, target: string = 'main', icon: boolean = false) {
    this.alert(AlertType.Error, content, title, keepAfterRouteChange, target, icon);
  }

  errors(errors: object, keepAfterRouteChange = false, target: string = null) {
    for (const key of Object.keys(errors)) {
      this.error(errors[key], null, keepAfterRouteChange, target || key);
    }
  }

  clear() {
    // clear alerts
    this.subject.next();
  }
}
