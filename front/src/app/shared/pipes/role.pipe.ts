import { Pipe, PipeTransform } from '@angular/core';
import { Role } from "../../core/classes/models/role";

@Pipe({
  name: 'role'
})
export class RolePipe implements PipeTransform {

  transform(role: Role, args?: any): string {
    const translationFR = {
      developer: "Développeur",
      administrator: "Administrator",
      staff: "Staff",
      student: "Etudiant"
    };

    return translationFR[role.name];
  }

}
