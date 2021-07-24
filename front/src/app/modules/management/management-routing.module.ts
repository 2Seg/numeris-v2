import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ParametersComponent } from './parameters/parameters.component';
import { AuthGuard } from '../../core/guards/auth.guard';

const managementRoutes: Routes = [
  {
    path: 'parametres',
    component: ParametersComponent,
    canActivate: [AuthGuard],
    data: {
      title: 'Paramètres',
    }
  },
];

@NgModule({
  imports: [
    RouterModule.forChild(managementRoutes)
  ],
  exports: [RouterModule]
})
export class ManagementRoutingModule { }
