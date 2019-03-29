import { Component, HostListener, OnInit } from '@angular/core';
import { ProjectService } from "../../../core/http/project.service";
import * as moment from "moment";
import { PaginatedProject } from "../../../core/classes/pagination/paginated-project";
import { IPopup } from "ng2-semantic-ui";

@Component({
  selector: 'app-project',
  templateUrl: './project.component.html',
  styleUrls: ['../mission/mission.component.css']
})
export class ProjectComponent implements OnInit {

  @HostListener('window:resize', ['$event'])
  onResize(event) {
    this.windowWidth = event.target.innerWidth;
  }

  windowWidth: number = window.innerWidth;

  paginatedProject: PaginatedProject;
  selectedStep: string;
  from: string;
  to: string;
  loading: boolean = false;
  options: string[] = [
    "Ouvert", "Validé",
    "Facturé", "Payé", "Cloturé"
  ];
  stepTranslations: string[] = [
    'hiring', 'validated',
    'billed', 'paid',
    'closed'
  ];
  constructor(private projectService: ProjectService) { }

  ngOnInit() {
    this.getProjectsPerPage(1);
  }

  reset(field: string) {
    if (this[field] !== undefined) this[field] = null;
    if (field == 'selectedStep') this.setFilter();
  }

  getProjectsPerPage(pageId?: number) {
    this.loading = true;
    this.projectService.getProjectsPerPage(
      pageId,
      this.selectedOptionToStep(),
      [this.dateToISO(this.from), this.dateToISO(this.to)]
    ).subscribe(paginatedProject => {
      this.paginatedProject = paginatedProject;
      this.loading = false;
    });
  }

  setFilter() {
    if (this.selectedStep !== undefined
      || this.from !== undefined
      || this.to !== undefined
    ) {
      this.getProjectsPerPage(1);
    }
  }

  selectedOptionToStep() {
    if (this.selectedStep !== undefined && this.selectedStep !== null) {
      const key = Object.keys(this.options)
        .find(key => this.options[key] === this.selectedStep);

      return this.stepTranslations[key];
    }

    return this.selectedStep;
  }

  dateToISO(date: string) {
    return date ? moment(date).toISOString() : null;
  }

  togglePopup(popup: IPopup, condition) {
    const widthCondition = this.windowWidth >= 1287
      || (this.windowWidth < 1200 && this.windowWidth > 1093);

    if (condition && widthCondition) {
      popup.toggle();
    }
  }
}
