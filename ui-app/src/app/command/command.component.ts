import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Params, Router} from "@angular/router";
import {Command} from "./command.model";
import {CommandsService} from "./commands.service";
import {FormControl, FormGroup} from "@angular/forms";
import {DataStorageService} from "../shared/data-storage.service";
import {LoaderService} from "../shared/loader.service";

@Component({
  selector: 'app-command',
  templateUrl: './command.component.html',
  styleUrls: ['./command.component.css']
})
export class CommandComponent implements OnInit {

  commandCode: string;
  command: Command;
  formGroup: FormGroup;
  private objectKeys = Object.keys;
  private output = null;

  constructor(private route: ActivatedRoute,
              private router: Router,
              private loader: LoaderService,
              private dataService: DataStorageService,
              private commandsService: CommandsService) {
  }

  ngOnInit() {
    this.route.params
      .subscribe(
        (params: Params) => {
          this.commandCode = params['command'];
          this.commandsService.commandsChanged.subscribe(() => {
            this.command = this.commandsService.getCommand(this.commandCode);
            this.formGroup = new FormGroup(this.constructInputs());
          });

          this.command = this.commandsService.getCommand(this.commandCode);
          this.formGroup = new FormGroup(this.constructInputs());
          this.output = null;
        }
      );
  }

  constructInputs() {
    let result = {};

    if (this.command) {
      this.command.arguments.forEach(item => {
        result[item.name] = new FormControl(null);
      });

      this.command.options.forEach(item => {
        result[item.name] = new FormControl(null);
      });

      return result;
    }

    return [];
  }

  runCommand() {
    let data = {
      'arguments': {}
    }, values: {} = this.formGroup.getRawValue(), keys = this.objectKeys(values);

    this.loader.putLoader();

    this.command.arguments.forEach(argument => {
      let index = keys.indexOf(argument.name);

      if (index > -1) {
        data.arguments[keys[index]] = values[keys[index]];
      }
    });

    this.dataService.runCommand(this.command, data).subscribe(response => {
      this.loader.hideLoader();
      this.output = response['output'];
    });
  }
}
