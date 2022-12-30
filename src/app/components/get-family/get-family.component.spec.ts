import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GetFamilyComponent } from './get-family.component';

describe('GetFamilyComponent', () => {
  let component: GetFamilyComponent;
  let fixture: ComponentFixture<GetFamilyComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GetFamilyComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(GetFamilyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
