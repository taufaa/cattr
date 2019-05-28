import {Item} from './item.model';
import {TimeInterval} from './timeinterval.model';

export interface ScreenshotData {
    id: number;
    time_interval_id?: number;
    path?: string;
    thumbnail_path?: string;
    deleted_at?: string;
    created_at?: string;
    updated_at?: string;
    time_interval?: TimeInterval;
    important?: boolean;
}

export class Screenshot extends Item {
    public id: number;
    public time_interval_id?: number;
    public path?: string;
    public thumbnail_path?: string;
    public deleted_at?: string;
    public created_at?: string;
    public updated_at?: string;
    public time_interval?: TimeInterval;
    public important?: boolean;

    constructor(data?: ScreenshotData) {
        super();

        if (data) {
            for (const key in data) {
                this[key] = data[key];
            }
        }
    }
}

export class ScreenshotsBlock {

    constructor(data) {
        if (data) {
            for (const key in data) {
                this[key] = data[key];
            }
        }

        this.intervals = Object.keys(this.intervals).map(key => this.intervals[key]);
    }

    public intervals: TimeInterval[][];
    public time: string;
}
