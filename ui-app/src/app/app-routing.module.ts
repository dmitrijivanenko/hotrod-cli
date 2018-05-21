import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CommandComponent } from "./command/command.component";
import {HomeComponent} from "./home/home.component";

const appRoutes: Routes = [
  { path: '', component: HomeComponent},
  { path: 'command/:command', component: CommandComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(appRoutes)],
  exports: [RouterModule]
})
export class AppRoutingModule {

}
