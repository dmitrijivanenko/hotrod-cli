import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';


import { AppComponent } from './app.component';
import { CommandComponent } from './command/command.component';
import { AppRoutingModule } from "./app-routing.module";
import { DataStorageService } from "./shared/data-storage.service";
import { HomeComponent } from './home/home.component';
import { HeaderComponent } from './header/header.component';
import { MenuComponent } from './menu/menu.component';
import {CommandsService} from "./command/commands.service";
import {ReactiveFormsModule} from "@angular/forms";
import {LoaderService} from "./shared/loader.service";

@NgModule({
  declarations: [
    AppComponent,
    CommandComponent,
    HomeComponent,
    HeaderComponent,
    MenuComponent
  ],
  imports: [
    HttpClientModule,
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule
  ],
  providers: [
    DataStorageService,
    CommandsService,
    LoaderService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
