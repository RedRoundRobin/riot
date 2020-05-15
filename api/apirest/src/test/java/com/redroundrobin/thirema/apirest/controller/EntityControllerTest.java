package com.redroundrobin.thirema.apirest.controller;

import com.redroundrobin.thirema.apirest.models.postgres.Entity;
import com.redroundrobin.thirema.apirest.models.postgres.Sensor;
import com.redroundrobin.thirema.apirest.models.postgres.User;
import com.redroundrobin.thirema.apirest.service.postgres.EntityService;
import com.redroundrobin.thirema.apirest.service.postgres.UserService;
import com.redroundrobin.thirema.apirest.service.timescale.LogService;
import com.redroundrobin.thirema.apirest.utils.JwtUtil;
import com.redroundrobin.thirema.apirest.utils.exception.ElementNotFoundException;
import com.redroundrobin.thirema.apirest.utils.exception.InvalidFieldsValuesException;
import com.redroundrobin.thirema.apirest.utils.exception.MissingFieldsException;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.mock.web.MockHttpServletRequest;
import org.springframework.test.context.junit4.SpringRunner;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.stream.Collectors;

import static org.junit.jupiter.api.Assertions.*;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.ArgumentMatchers.anyInt;
import static org.mockito.ArgumentMatchers.anyString;
import static org.mockito.Mockito.doNothing;
import static org.mockito.Mockito.when;

@RunWith(SpringRunner.class)
public class EntityControllerTest {

  private EntityController entityController;

  @MockBean
  private JwtUtil jwtUtil;

  @MockBean
  private LogService logService;

  @MockBean
  private UserService userService;

  @MockBean
  private EntityService entityService;

  MockHttpServletRequest httpRequest;

  private final String userTokenWithBearer = "Bearer userToken";
  private final String modTokenWithBearer = "Bearer modToken";
  private final String adminTokenWithBearer = "Bearer adminToken";
  private final String userToken = "userToken";
  private final String modToken = "modToken";
  private final String adminToken = "adminToken";

  private User admin;
  private User mod;
  private User user;

  private Entity entity1;
  private Entity entity2;
  private Entity entity3;

  private Sensor sensor1;
  private Sensor sensor2;
  private Sensor sensor3;

  List<Sensor> allSensors;
  List<Entity> allEntities;

  Set<Sensor> entity1And2Sensors;
  Set<Sensor> entity3Sensors;

  @Before
  public void setUp() throws MissingFieldsException, InvalidFieldsValuesException {
    entityController = new EntityController(entityService, jwtUtil, logService, userService);

    httpRequest = new MockHttpServletRequest();
    httpRequest.setRemoteAddr("localhost");

    // ----------------------------------------- Set Users --------------------------------------
    admin = new User(1, "admin", "admin", "admin", "pass", User.Role.ADMIN);
    mod = new User(3, "mod", "mod", "mod", "pass", User.Role.MOD);
    user = new User(2, "user", "user", "user", "user", User.Role.USER);

    List<User> allUsers = new ArrayList<>();
    allUsers.add(admin);
    allUsers.add(mod);
    allUsers.add(user);

    // ----------------------------------------- Set Entities --------------------------------------
    entity1 = new Entity(1, "entity1", "loc1");
    entity2 = new Entity(2, "entity2", "loc2");
    entity3 = new Entity(3, "entity3", "loc3");

    allEntities = new ArrayList<>();
    allEntities.add(entity1);
    allEntities.add(entity2);
    allEntities.add(entity3);

    // ----------------------------------------- Set Sensors --------------------------------------
    sensor1 = new Sensor(1, "type1", 1);
    sensor2 = new Sensor(2, "type2", 2);
    sensor3 = new Sensor(3, "type3", 3);

    allSensors = new ArrayList<>();
    allSensors.add(sensor1);
    allSensors.add(sensor2);
    allSensors.add(sensor3);

    // -------------------------------- Set sensors to entities ----------------------------------
    entity1And2Sensors = new HashSet<>();
    entity1And2Sensors.add(sensor1);
    entity1And2Sensors.add(sensor2);
    entity1.setSensors(entity1And2Sensors);
    entity2.setSensors(entity1And2Sensors);

    entity3Sensors = new HashSet<>();
    entity3Sensors.add(sensor3);
    entity3.setSensors(entity3Sensors);

    // ------------------------------- Set entities to users -----------------------------------
    user.setEntity(entity1);
    mod.setEntity(entity1);

    // Core Controller needed mock
    when(jwtUtil.extractUsername(userToken)).thenReturn(user.getEmail());
    when(jwtUtil.extractUsername(modToken)).thenReturn(mod.getEmail());
    when(jwtUtil.extractUsername(adminToken)).thenReturn(admin.getEmail());
    when(jwtUtil.extractType(anyString())).thenReturn("webapp");
    when(userService.findByEmail(admin.getEmail())).thenReturn(admin);
    when(userService.findByEmail(mod.getEmail())).thenReturn(mod);
    when(userService.findByEmail(user.getEmail())).thenReturn(user);

    when(entityService.findAll()).thenReturn(allEntities);
    when(entityService.findAllBySensorId(anyInt())).thenAnswer(i -> {
      Sensor sensor = allSensors.stream()
          .filter(s -> i.getArgument(0).equals(s.getId()))
          .findFirst().orElse(null);
      if (sensor != null) {
        return allEntities.stream()
            .filter(e -> e.getSensors().contains(sensor))
            .collect(Collectors.toList());
      } else {
        return Collections.emptyList();
      }
    });
    when(entityService.findAllBySensorIdAndUserId(anyInt(), anyInt())).thenAnswer(i -> {
      Sensor sensor = allSensors.stream()
          .filter(s -> i.getArgument(0).equals(s.getId()))
          .findFirst().orElse(null);
      User user = allUsers.stream()
          .filter(u -> i.getArgument(1).equals(u.getId()))
          .findFirst().orElse(null);
      if (sensor != null && user != null) {
        return allEntities.stream()
            .filter(e -> e.getSensors().contains(sensor) && user.getEntity().equals(e))
            .collect(Collectors.toList());
      } else {
        return Collections.emptyList();
      }
    });
    when(entityService.findAllByUserId(anyInt())).thenAnswer(i -> {
      User user = allUsers.stream()
          .filter(u -> i.getArgument(0).equals(u.getId()))
          .findFirst().orElse(null);
      if (user != null) {
        return allEntities.stream()
            .filter(e -> user.getEntity().equals(e))
            .collect(Collectors.toList());
      } else {
        return Collections.emptyList();
      }
    });
    when(entityService.findById(anyInt())).thenAnswer(i -> {
      return allEntities.stream().filter(e -> i.getArgument(0).equals(e.getId())).findFirst().orElse(null);
    });
    when(entityService.addEntity(any(Map.class))).thenAnswer(i -> {
      Map<String, Object> newEntityFields = i.getArgument(0);
      if (newEntityFields.containsKey("name") && newEntityFields.containsKey("location")) {
        return new Entity((String)newEntityFields.get("name"), (String)newEntityFields.get("location"));
      } else {
        throw MissingFieldsException.defaultMessage();
      }
    });
    when(entityService.editEntity(any(Integer.class), any(Map.class))).thenAnswer(i -> {
      Integer entityId = i.getArgument(0);
      Map<String, Object> newEntityFields = i.getArgument(1);
      if (allEntities.stream().anyMatch(e -> entityId.equals(e.getId()))) {
        if (!newEntityFields.containsKey("name") && !newEntityFields.containsKey("location")) {
          throw MissingFieldsException.defaultMessage();
        } else {
          Entity entity = entityService.findById(entityId);
          if (newEntityFields.containsKey("name")) {
            entity.setName((String)newEntityFields.get("name"));
          }
          if (newEntityFields.containsKey("location")) {
            entity.setLocation((String)newEntityFields.get("location"));
          }
          return entity;
        }
      } else {
        throw new InvalidFieldsValuesException("Entity Id not found");
      }
    });
  }

  @Test
  public void getAllEntitiesBySensorIdAndUserIdByAdmin() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(
        adminTokenWithBearer, sensor1.getId(), user.getId());

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(user.getEntity(), response.getBody().get(0));
  }

  @Test
  public void getAllEntitiesByAdmin() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(adminTokenWithBearer, null, null);

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(allEntities, response.getBody());
  }

  @Test
  public void getAllEntitiesBySensorByAdmin() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(
        adminTokenWithBearer, sensor1.getId(), null);

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertFalse(response.getBody().isEmpty());
  }

  @Test
  public void getAllEntitiesByUserIdByAdmin() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(adminTokenWithBearer, null, user.getId());

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(user.getEntity(), response.getBody().get(0));
  }

  @Test
  public void getAllEntitiesByUserIdByMod() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(modTokenWithBearer, null, user.getId());

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(mod.getEntity(), response.getBody().get(0));
  }

  @Test
  public void getAllEntitiesByUser() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(userTokenWithBearer, null, null);

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(user.getEntity(), response.getBody().get(0));
  }

  @Test
  public void getAllEntitiesByUserWithDifferentUserId() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(userTokenWithBearer, null, admin.getId());

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(Collections.emptyList(), response.getBody());
  }

  @Test
  public void getAllEntitiesBySensorId() {
    ResponseEntity<List<Entity>> response = entityController.getEntities(userTokenWithBearer, sensor1.getId(), null);

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(user.getEntity(), response.getBody().stream().findFirst().orElse(null));
  }

  @Test
  public void getEntityByIdByAdminSuccessfull() {
    ResponseEntity<Entity> response = entityController.getEntity(adminTokenWithBearer, entity3.getId());

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(entity3, response.getBody());
  }

  @Test
  public void getEntityByIdByUserNotAllowerError403() {
    ResponseEntity<Entity> response = entityController.getEntity(userTokenWithBearer, entity3.getId());

    assertEquals(HttpStatus.FORBIDDEN, response.getStatusCode());
  }



  @Test
  public void addEntityByAdminSuccessfull() {
    String name = "nome";
    String location = "location";

    Map<String, Object> newEntityFields = new HashMap<>();
    newEntityFields.put("name", name);
    newEntityFields.put("location", location);

    ResponseEntity<Entity> response = entityController.addEntity(adminTokenWithBearer, newEntityFields, httpRequest);

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(name, response.getBody().getName());
    assertEquals(location, response.getBody().getLocation());
  }

  @Test
  public void addEntityByUserNotAuthorizedError403() {
    String name = "nome";
    String location = "location";

    Map<String, Object> newEntityFields = new HashMap<>();
    newEntityFields.put("name", name);
    newEntityFields.put("location", location);

    ResponseEntity<Entity> response = entityController.addEntity(userTokenWithBearer, newEntityFields, httpRequest);

    assertEquals(HttpStatus.FORBIDDEN, response.getStatusCode());
  }

  @Test
  public void addEntityByAdminMissingFieldsError400() {
    String name = "nome";
    String location = "location";

    Map<String, Object> newEntityFields = new HashMap<>();
    newEntityFields.put("name", name);

    ResponseEntity<Entity> response = entityController.addEntity(adminTokenWithBearer, newEntityFields, httpRequest);

    assertEquals(HttpStatus.BAD_REQUEST, response.getStatusCode());
  }



  @Test
  public void editEntityByAdminSuccessfull() {
    String name = "nome";
    String location = "locazione";

    Map<String, Object> fieldsToEdit = new HashMap<>();
    fieldsToEdit.put("name", name);
    fieldsToEdit.put("location", location);

    ResponseEntity<Entity> response = entityController.editEntity(adminTokenWithBearer, entity1.getId(), fieldsToEdit, httpRequest);

    assertEquals(HttpStatus.OK, response.getStatusCode());
    assertEquals(name, response.getBody().getName());
    assertEquals(location, response.getBody().getLocation());
  }

  @Test
  public void editEntityByUserNotAuthorizedError403() {
    String name = "nome";
    String location = "location";

    Map<String, Object> fieldsToEdit = new HashMap<>();
    fieldsToEdit.put("name", name);
    fieldsToEdit.put("location", location);

    ResponseEntity<Entity> response = entityController.editEntity(userTokenWithBearer, entity1.getId(), fieldsToEdit, httpRequest);

    assertEquals(HttpStatus.FORBIDDEN, response.getStatusCode());
  }

  @Test
  public void editEntityByAdminByNotExistentEntityIdError400() {
    String name = "nome";
    String location = "location";

    Map<String, Object> fieldsToEdit = new HashMap<>();
    fieldsToEdit.put("name", name);
    fieldsToEdit.put("location", location);

    ResponseEntity<Entity> response = entityController.editEntity(adminTokenWithBearer, 9, fieldsToEdit, httpRequest);

    assertEquals(HttpStatus.BAD_REQUEST, response.getStatusCode());
  }

  @Test
  public void editEntitySensorsByAdminSuccessfull() {
    Map<String, Object> fieldsToEdit = new HashMap<>();
    fieldsToEdit.put("enableOrDisableSensors", true);
    List<Integer> list = new ArrayList<>();
    list.add(sensor3.getId());
    fieldsToEdit.put("toInsert", list);
    fieldsToEdit.put("toDelete", list);

    try {
      when(entityService.enableOrDisableSensorToEntity(entity1.getId(), fieldsToEdit)).thenReturn(true);
    } catch (ElementNotFoundException e) {
      e.printStackTrace();
    } catch (MissingFieldsException e) {
      e.printStackTrace();
    }

    ResponseEntity<Entity> response = entityController.editEntity(adminTokenWithBearer, entity1.getId(), fieldsToEdit, httpRequest);

    assertEquals(HttpStatus.OK, response.getStatusCode());
  }

  @Test
  public void editEntitySensorsByAdminWithMissingFieldsError400() {

    Map<String, Object> fieldsToEdit = new HashMap<>();
    fieldsToEdit.put("enableOrDisableSensors", true);
    List<Integer> list = new ArrayList<>();
    list.add(sensor3.getId());

    try {
      when(entityService.enableOrDisableSensorToEntity(entity1.getId(), fieldsToEdit)).thenThrow(MissingFieldsException.defaultMessage());
    } catch (ElementNotFoundException e) {
      e.printStackTrace();
    } catch (MissingFieldsException e) {
      e.printStackTrace();
    }

    ResponseEntity<Entity> response = entityController.editEntity(adminTokenWithBearer, entity1.getId(), fieldsToEdit, httpRequest);

    assertEquals(HttpStatus.BAD_REQUEST, response.getStatusCode());
  }

  @Test
  public void editEntitySensorsByAdminError500() {
    Map<String, Object> fieldsToEdit = new HashMap<>();
    fieldsToEdit.put("enableOrDisableSensors", true);
    List<Integer> list = new ArrayList<>();
    list.add(sensor3.getId());
    fieldsToEdit.put("toInsert", list);
    fieldsToEdit.put("toDelete", list);

    try {
      when(entityService.enableOrDisableSensorToEntity(entity1.getId(), fieldsToEdit)).thenReturn(false);
    } catch (ElementNotFoundException e) {
      e.printStackTrace();
    } catch (MissingFieldsException e) {
      e.printStackTrace();
    }

    ResponseEntity<Entity> response = entityController.editEntity(adminTokenWithBearer, entity1.getId(), fieldsToEdit, httpRequest);

    assertEquals(HttpStatus.INTERNAL_SERVER_ERROR, response.getStatusCode());
  }



  @Test
  public void deleteEntityByAdminSuccessfull() {
    try {
      when(entityService.deleteEntity(any(Integer.class))).thenReturn(true);
    } catch (ElementNotFoundException e) {
      e.printStackTrace();
    }

    ResponseEntity response = entityController.deleteEntity(adminTokenWithBearer, entity1.getId(), httpRequest);

    assertEquals(HttpStatus.OK, response.getStatusCode());
  }

  @Test
  public void deleteEntityByAdminSimulateDbError409() {
    try {
      when(entityService.deleteEntity(any(Integer.class))).thenReturn(false);
    } catch (ElementNotFoundException e) {
      e.printStackTrace();
    }

    ResponseEntity response = entityController.deleteEntity(adminTokenWithBearer, entity1.getId(), httpRequest);

    assertEquals(HttpStatus.CONFLICT, response.getStatusCode());
  }

  @Test
  public void deleteEntityByAdminByNotExistentEntityIdError400() {
    try {
      when(entityService.deleteEntity(any(Integer.class))).thenThrow(new ElementNotFoundException(""));
    } catch (ElementNotFoundException e) {
      e.printStackTrace();
    }

    ResponseEntity response = entityController.deleteEntity(adminTokenWithBearer, 9, httpRequest);

    assertEquals(HttpStatus.BAD_REQUEST, response.getStatusCode());
  }

  @Test
  public void deleteEntityByUserError403() {

    ResponseEntity response = entityController.deleteEntity(userTokenWithBearer, entity1.getId(), httpRequest);

    assertEquals(HttpStatus.FORBIDDEN, response.getStatusCode());
  }
}
