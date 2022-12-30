import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailCiteComponent } from './detail-cite.component';

describe('DetailCiteComponent', () => {
  let component: DetailCiteComponent;
  let fixture: ComponentFixture<DetailCiteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DetailCiteComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(DetailCiteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
