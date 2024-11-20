import { ComponentFixture, TestBed } from '@angular/core/testing';
import { TeamPlanningPage } from './team-planning.page';

describe('TeamPlanningPage', () => {
  let component: TeamPlanningPage;
  let fixture: ComponentFixture<TeamPlanningPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(TeamPlanningPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
