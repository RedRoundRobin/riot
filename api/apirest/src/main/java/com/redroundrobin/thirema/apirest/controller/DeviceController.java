package com.redroundrobin.thirema.apirest.controller;

import com.redroundrobin.thirema.apirest.models.postgres.Device;
import com.redroundrobin.thirema.apirest.models.postgres.Sensor;
import com.redroundrobin.thirema.apirest.models.postgres.User;
import com.redroundrobin.thirema.apirest.service.postgres.DeviceService;
import com.redroundrobin.thirema.apirest.service.postgres.SensorService;
import com.redroundrobin.thirema.apirest.service.postgres.UserService;
import com.redroundrobin.thirema.apirest.service.timescale.LogService;
import com.redroundrobin.thirema.apirest.utils.JwtUtil;
import com.redroundrobin.thirema.apirest.utils.exception.ConflictException;
import com.redroundrobin.thirema.apirest.utils.exception.ElementNotFoundException;
import com.redroundrobin.thirema.apirest.utils.exception.InvalidFieldsValuesException;
import com.redroundrobin.thirema.apirest.utils.exception.MissingFieldsException;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.media.ArraySchema;
import io.swagger.v3.oas.annotations.media.Content;
import io.swagger.v3.oas.annotations.media.ExampleObject;
import io.swagger.v3.oas.annotations.media.Schema;
import io.swagger.v3.oas.annotations.responses.ApiResponse;
import java.util.Collections;
import java.util.List;
import java.util.Map;
import javax.servlet.http.HttpServletRequest;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestHeader;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

@RestController
@RequestMapping(value = {"/devices"})
public class DeviceController extends CoreController {

  protected Logger logger = LoggerFactory.getLogger(this.getClass());

  private final DeviceService deviceService;

  private final SensorService sensorService;

  @Autowired
  public DeviceController(DeviceService deviceService, SensorService sensorService, JwtUtil jwtUtil,
                          LogService logService, UserService userService) {
    super(jwtUtil, logService, userService);
    this.deviceService = deviceService;
    this.sensorService = sensorService;
  }


  @Operation(
      summary = "See a list of all the devices you have access to",
      description = "This request allows you to see all the devices you have access to. You can"
          + " also filter this research by either giving in input the entityId and/or the "
          + "gatewayId, or by the cmdEnabled parameter. This last filter is available for "
          + "administrators only.",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  array = @ArraySchema(schema = @Schema(implementation = Device.class))
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @GetMapping(value = {""})
  public ResponseEntity<List<Device>> getDevices(
      @RequestHeader("Authorization") String authorization,
      @RequestParam(value = "entityId", required = false) Integer entityId,
      @RequestParam(value = "gatewayId", required = false) Integer gatewayId,
      @RequestParam(value = "cmdEnabled", required = false) Boolean cmdEnabled) {
    User user = this.getUserFromAuthorization(authorization);
    if (cmdEnabled != null && user.getType() != User.Role.ADMIN) {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity(HttpStatus.FORBIDDEN);
    }
    // (cmdEnabled == null || user.getType() == User.Role.ADMIN) = true
    if (user.getType() == User.Role.ADMIN) {
      if (cmdEnabled != null && (entityId != null || gatewayId != null)) {
        return new ResponseEntity(HttpStatus.NOT_FOUND);
      }
      if (cmdEnabled != null) {
        return ResponseEntity.ok(deviceService.getEnabled(cmdEnabled));
      }
      if (entityId != null && gatewayId != null) {
        return ResponseEntity.ok(deviceService.findAllByEntityIdAndGatewayId(entityId, gatewayId));
      } else if (gatewayId != null) {
        return ResponseEntity.ok(deviceService.findAllByGatewayId(gatewayId));
      } else if (entityId != null) {
        return ResponseEntity.ok(deviceService.findAllByEntityId(entityId));
      } else {
        return ResponseEntity.ok(deviceService.findAll());
      }
    } else {
      if (gatewayId != null && (entityId == null || user.getEntity().getId() == entityId)) {
        return ResponseEntity.ok(deviceService.findAllByEntityIdAndGatewayId(
            user.getEntity().getId(), gatewayId));
      } else if (entityId == null || user.getEntity().getId() == entityId) {
        return ResponseEntity.ok(deviceService.findAllByEntityId(user.getEntity().getId()));
      } else {
        return ResponseEntity.ok(Collections.emptyList());
      }
    }
  }


  @Operation(
      summary = "See the details of a single device",
      description = "This request allows you to see the details of a single device",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  schema = @Schema(implementation = Device.class)
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @GetMapping(value = {"/{deviceId:.+}"})
  public ResponseEntity<Device> getDevice(@RequestHeader("Authorization") String authorization,
                          @PathVariable("deviceId") int deviceId) {
    User user = this.getUserFromAuthorization(authorization);
    if (user.getType() == User.Role.ADMIN) {
      return ResponseEntity.ok(deviceService.findById(deviceId));
    } else {
      return ResponseEntity.ok(
          deviceService.findByIdAndEntityId(deviceId, user.getEntity().getId()));
    }
  }


  @Operation(
      summary = "Get access to the sensors of a single device",
      description = "This request returns a list of all the sensors of a device",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  array = @ArraySchema(schema = @Schema(implementation = Sensor.class))
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @GetMapping(value = {"/{deviceId:.+}/sensors"})
  public ResponseEntity<List<Sensor>> getSensorsByDevice(
      @RequestHeader("Authorization") String authorization,
      @PathVariable("deviceId") int deviceId,
      @RequestParam(value = "cmdEnabled", required = false) Boolean cmdEnabled) {
    User user = this.getUserFromAuthorization(authorization);
    if (cmdEnabled != null && user.getType() != User.Role.ADMIN) {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity(HttpStatus.FORBIDDEN);
    }
    if (user.getType() == User.Role.ADMIN) {
      if (cmdEnabled != null) {
        return ResponseEntity.ok(deviceService.getEnabledSensorsDevice(cmdEnabled, deviceId));
      }
      return ResponseEntity.ok(sensorService.findAllByDeviceId(deviceId));
    } else {
      return ResponseEntity.ok(
          sensorService.findAllByDeviceIdAndEntityId(deviceId, user.getEntity().getId()));
    }
  }


  @Operation(
      summary = "Get access to a single sensor of the given device",
      description = "This request allows you to see the details of a single sensor connected "
          + "to a device",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  schema = @Schema(implementation = Sensor.class)
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @GetMapping(value = {"/{deviceId:.+}/sensors/{realSensorId:.+}"})
  public ResponseEntity<Sensor> getSensorByDevice(
      @RequestHeader("Authorization") String authorization,
      @PathVariable("deviceId") int deviceId,
      @PathVariable("realSensorId") int realSensorId) {
    User user = this.getUserFromAuthorization(authorization);
    if (user.getType() == User.Role.ADMIN) {
      return ResponseEntity.ok(sensorService.findByDeviceIdAndRealSensorId(deviceId, realSensorId));
    } else {
      return ResponseEntity.ok(sensorService.findByDeviceIdAndRealSensorIdAndEntityId(deviceId,
          realSensorId, user.getEntity().getId()));
    }
  }

  @Operation(
      summary = "Inserting a device in the database",
      description = "This request is available for administrators only. It allows you to create"
          + " a new device",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  schema = @Schema(implementation = Device.class)
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "409",
              description = "Conflict. Database error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @PostMapping(value = {""})
  public ResponseEntity<Device> createDevice(
      @RequestHeader("authorization") String authorization,
      @RequestBody Map<String, Object> newDeviceFields,
      HttpServletRequest httpRequest) {
    User user = this.getUserFromAuthorization(authorization);

    if (user.getType() == User.Role.ADMIN) {
      try {
        Device device = deviceService.addDevice(newDeviceFields);
        logService.createLog(user.getId(), getIpAddress(httpRequest), "device.add",
            "D#" + device.getId());
        return ResponseEntity.ok(device);
      } catch (MissingFieldsException | InvalidFieldsValuesException fe) {
        logger.debug(fe.toString());
        return new ResponseEntity(HttpStatus.BAD_REQUEST);
      } catch (ConflictException ce) {
        logger.debug(ce.toString());
        return new ResponseEntity(HttpStatus.CONFLICT);
      }
    } else {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity(HttpStatus.FORBIDDEN);
    }
  }


  @Operation(
      summary = "Inserting a sensor in the database",
      description = "This request is available for administrators only. It allows you to create"
          + " a new sensor ant connect it to a device",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  schema = @Schema(implementation = Sensor.class)
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "409",
              description = "Conflict. Database error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @PostMapping(value = {"/{deviceId:.+}/sensors"})
  public ResponseEntity<Sensor> createSensor(
      @RequestHeader("authorization") String authorization,
      @RequestBody Map<String, Object> newSensorFields,
      @PathVariable("deviceId") int deviceId,
      HttpServletRequest httpRequest) {
    User user = this.getUserFromAuthorization(authorization);

    if (user.getType() == User.Role.ADMIN) {
      try {
        newSensorFields.put("deviceId", deviceId);
        Sensor sensor = sensorService.addSensor(newSensorFields);
        logService.createLog(user.getId(), getIpAddress(httpRequest), "sensor.add",
            "D#" + deviceId + " - S@" + sensor.getRealSensorId());
        return ResponseEntity.ok(sensor);
      } catch (MissingFieldsException | InvalidFieldsValuesException fe) {
        logger.debug(fe.toString());
        return new ResponseEntity(HttpStatus.BAD_REQUEST);
      } catch (ConflictException ce) {
        logger.debug(ce.toString());
        return new ResponseEntity(HttpStatus.CONFLICT);
      }
    } else {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity(HttpStatus.FORBIDDEN);
    }
  }


  @Operation(
      summary = "Editing a device",
      description = "This request is available for administrators only. It allows you to edit"
          + " a device already saved in the database.",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject(
                          name = "Success",
                          value = "{\"gatewayId\": \"int\","
                              + "\"frequency\": \"int\","
                              + "\"realDeviceId\": \"int\","
                              + "\"name\": \"String\","
                              + "\"deviceId\": \"int\"}"
                      )
                  }
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "409",
              description = "Conflict. Database error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @PutMapping(value = {"/{deviceId:.+}"})
  public ResponseEntity editDevice(
      @RequestHeader("authorization") String authorization,
      @RequestBody Map<String, Object> fieldsToEdit,
      @PathVariable("deviceId") int deviceId,
      HttpServletRequest httpRequest) {
    User user = this.getUserFromAuthorization(authorization);

    if (user.getType() == User.Role.ADMIN) {
      try {
        Device device = deviceService.editDevice(deviceId, fieldsToEdit);
        logService.createLog(user.getId(), getIpAddress(httpRequest), "device.edit",
            "D#" + deviceId);
        return ResponseEntity.ok(device);
      } catch (MissingFieldsException | InvalidFieldsValuesException | ElementNotFoundException e) {
        logger.debug(e.toString());
        return new ResponseEntity(HttpStatus.BAD_REQUEST);
      } catch (ConflictException ce) {
        logger.debug(ce.toString());
        return new ResponseEntity(HttpStatus.CONFLICT);
      }
    } else {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity(HttpStatus.FORBIDDEN);
    }
  }


  @Operation(
      summary = "Editing a sensor",
      description = "This request is available for administrators only. It allows you to edit"
          + " a sensor already saved in the database.",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                  @ExampleObject(
                      name = "Success",
                      value = "{\"sensorId\": \"int\","
                          + "\"realSensorId\": \"int\","
                          + "\"cmdEnabled\": \"boolean\","
                          + "\"type\": \"String\","
                          + "\"deviceId\": \"int\"}"
                  )}
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "409",
              description = "Conflict. Database error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @PutMapping(value = {"/{deviceId:.+}/sensors/{realSensorId:.+}"})
  public ResponseEntity<Sensor> editSensor(
      @RequestHeader("authorization") String authorization,
      @RequestBody Map<String, Object> fieldsToEdit,
      @PathVariable("deviceId") int deviceId,
      @PathVariable("realSensorId") int realSensorId,
      HttpServletRequest httpRequest) {
    User user = this.getUserFromAuthorization(authorization);

    if (user.getType() == User.Role.ADMIN) {
      try {
        Sensor sensor = sensorService.editSensor(realSensorId, deviceId, fieldsToEdit);
        logService.createLog(user.getId(), getIpAddress(httpRequest), "sensor.edit",
            "D#" + deviceId + " - S@" + sensor.getRealSensorId());
        return ResponseEntity.ok(sensor);
      } catch (MissingFieldsException | InvalidFieldsValuesException | ElementNotFoundException e) {
        logger.debug(e.toString());
        return new ResponseEntity(HttpStatus.BAD_REQUEST);
      } catch (ConflictException ce) {
        logger.debug(ce.toString());
        return new ResponseEntity(HttpStatus.CONFLICT);
      }
    } else {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity(HttpStatus.FORBIDDEN);
    }
  }

  
  @Operation(
      summary = "Deleting a device",
      description = "This request is available for administrators only. It allows you to delete"
          + " a device from the database.",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json"
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "409",
              description = "Conflict. Database error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
    })
  @DeleteMapping(value = {"/{deviceId:.+}"})
  public ResponseEntity deleteDevice(@RequestHeader("authorization") String authorization,
                                    @PathVariable("deviceId") int deviceId,
                                    HttpServletRequest httpRequest) {
    User user = getUserFromAuthorization(authorization);

    if (user.getType() == User.Role.ADMIN) {
      try {
        if (deviceService.deleteDevice(deviceId)) {
          logService.createLog(user.getId(), getIpAddress(httpRequest), "device.delete",
              "D#" + deviceId);
          return new ResponseEntity<>(HttpStatus.OK);
        } else {
          logger.debug("RESPONSE STATUS: INTERNAL_SERVER_ERROR. Alert " + deviceId
              + " is not been deleted due to a database error");
          return new ResponseEntity<>(HttpStatus.CONFLICT);
        }
      } catch (ElementNotFoundException e) {
        logger.debug(e.toString());
        return new ResponseEntity<>(HttpStatus.BAD_REQUEST);
      }
    } else {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity<>(HttpStatus.FORBIDDEN);
    }
  }


  @Operation(
      summary = "Deleting a sensor",
      description = "This request is available for administrators only. It allows you to delete"
          + " a sensor from the database.",
      responses = {
          @ApiResponse(
              responseCode = "200",
              description = "The request is successful",
              content = @Content(
                  mediaType = "application/json"
              )),
          @ApiResponse(
              responseCode = "400",
              description = "There is an error in the request",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "401",
              description = "The authentication failed",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "403",
              description = "Not authorized. Only admins can do it",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "409",
              description = "Conflict. Database error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          ),
          @ApiResponse(
              responseCode = "500",
              description = "Server error",
              content = @Content(
                  mediaType = "application/json",
                  examples = {
                      @ExampleObject()
                  }
              )
          )
      })
  @DeleteMapping(value = {"/{deviceId:.+}/sensors/{realSensorId:.+}"})
  public ResponseEntity deleteSensor(@RequestHeader("authorization") String authorization,
                                     @PathVariable("deviceId") int deviceId,
                                     @PathVariable("realSensorId") int realSensorId,
                                     HttpServletRequest httpRequest) {
    User user = getUserFromAuthorization(authorization);

    if (user.getType() == User.Role.ADMIN) {
      try {
        if (sensorService.deleteSensor(deviceId, realSensorId)) {
          logService.createLog(user.getId(), getIpAddress(httpRequest), "sensor.delete",
              "D#" + deviceId + " - S@" + realSensorId);
          return new ResponseEntity<>(HttpStatus.OK);
        } else {
          logger.debug("RESPONSE STATUS: INTERNAL_SERVER_ERROR. Alert " + realSensorId
              + " is not been deleted due to a database error");
          return new ResponseEntity<>(HttpStatus.CONFLICT);
        }
      } catch (ElementNotFoundException e) {
        logger.debug(e.toString());
        return new ResponseEntity<>(HttpStatus.BAD_REQUEST);
      }
    } else {
      logger.debug("RESPONSE STATUS: FORBIDDEN. User " + user.getId()
          + " is not an Administrator.");
      return new ResponseEntity<>(HttpStatus.FORBIDDEN);
    }
  }
}
