import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SuiModule } from "ng2-semantic-ui";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { SharedModule } from "../../../shared/shared.module";
import { HttpClientModule } from "@angular/common/http";
import { MissionRoutingModule } from "./mission-routing.module";
import { MissionIndexComponent } from './mission-index/mission-index.component';
import { MissionListComponent } from './mission-list/mission-list.component';
import { MissionComponent } from "./mission.component";
import { MissionShowComponent } from './mission-show/mission-show.component';
import { StudentModule } from "../../student/student.module";
import { MissionApplicationsComponent } from './mission-applications/mission-applications.component';
import { ApplicationListComponent } from './mission-applications/application-list/application-list.component';
import { UserModule } from "../user/user.module";
import { MissionCreateComponent } from './mission-create/mission-create.component';
import { MissionFormComponent } from './mission-form/mission-form.component';
import { MissionEditComponent } from './mission-edit/mission-edit.component';
import { MissionDeleteModalComponent } from './mission-delete-modal/mission-delete-modal.component';

@NgModule({
  imports: [
    CommonModule,
    SuiModule,
    FormsModule,
    ReactiveFormsModule,
    SharedModule,
    HttpClientModule,
    MissionRoutingModule,
    StudentModule,
    UserModule,
  ],
  declarations: [
    MissionComponent,
    MissionIndexComponent,
    MissionListComponent,
    MissionShowComponent,
    MissionApplicationsComponent,
    ApplicationListComponent,
    MissionCreateComponent,
    MissionFormComponent,
    MissionEditComponent,
    MissionDeleteModalComponent
  ],
  exports: [
    MissionListComponent,
    MissionFormComponent,
  ],
  entryComponents: [
    MissionDeleteModalComponent,
  ],
})
export class MissionModule { }
