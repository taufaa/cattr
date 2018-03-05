import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../../api/api.service';
import {TimeInterval} from "../../../models/timeinterval.model";
import {Router} from "@angular/router";
import {TimeIntervalsService} from "../timeintervals.service";
import {ItemsCreateComponent} from "../../items.create.component";

@Component({
    selector: 'app-timeintervals-create',
    templateUrl: './timeintervals.create.component.html',
    styleUrls: ['../../items.component.scss']
})
export class TimeIntervalsCreateComponent extends ItemsCreateComponent implements OnInit {

    public item: TimeInterval = new TimeInterval();

    constructor(api: ApiService,
                timeIntervalService: TimeIntervalsService,
                router: Router) {
        super(api, timeIntervalService, router);
    }

    prepareData() {
        return {
            'task_id': this.item.task_id,
            'start_at': this.item.start_at,
            'end_at': this.item.end_at
        }
    }
}