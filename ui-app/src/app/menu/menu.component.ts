import { Component, OnInit } from '@angular/core';
import {Command} from "../command/command.model";
import {Subscription} from "rxjs/Rx";
import {CommandsService} from "../command/commands.service";

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit {
  commands: Command[];
  subscription: Subscription;
  objectKeys = Object.keys;

  constructor(protected commandService: CommandsService) {
  }

  ngOnInit() {
    this.subscription = this.commandService.commandsChanged
      .subscribe((commands: Command[]) => {
        this.commands = commands;
      });

    this.commands = this.commandService.getCommands();
  }

}
